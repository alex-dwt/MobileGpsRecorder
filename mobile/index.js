import React from 'react';
import { StyleSheet, Text, View, Alert, PermissionsAndroid, BackHandler } from 'react-native';
import MapView from 'react-native-maps';
import { Marker } from 'react-native-maps';
import { AppRegistry } from "react-native";
import { name as appName } from "./app.json";
import BackgroundJob from 'react-native-background-job';
const qs = require('qs');
import axios from 'axios';
const PushNotification = require('react-native-push-notification');

const BACKGROUND_JOB_ID = 'BACKGROUND_JOB';

let lastSavedPositionDate = null;

const URL = 'http://192.168.100.6/api/places';


PushNotification.configure({
    // (optional) Called when Token is generated (iOS and Android)
    onRegister: function(token) {
        console.log( 'TOKEN:', token );
    },
    // (required) Called when a remote or local notification is opened or received
    onNotification: function(notification) {
        console.log( 'NOTIFICATION:', notification );
        // process the notification
    },

    // ANDROID ONLY: GCM or FCM Sender ID (product_number) (optional - not required for local notifications, but is need to receive remote push notifications)
    // senderID: "YOUR GCM (OR FCM) SENDER ID",

    // Should the initial notification be popped automatically
    // default: true
    popInitialNotification: true,

    /**
     * (optional) default: true
     * - Specified if permissions (ios) and token (android and ios) will requested or not,
     * - if not, you must call PushNotificationsHandler.requestPermissions() later
     */
    requestPermissions: true,
});


const saveLocation = async (force = false) => {
    console.log(lastSavedPositionDate);

    if (force
        || !lastSavedPositionDate
        || new Date(lastSavedPositionDate.getTime() + 1*60000) < new Date()
    ) {
        const location = await getCurrentLatLon();

        if (location) {
            try {
                await axios.post(URL, location);

                lastSavedPositionDate = new Date();
                console.log('location saved success');
                console.log(location);
            } catch (e) {
                console.log('location NOT SAVED at server');
                console.log(e);
            }
        } else {
            console.log('location NOT GETTED FROM GPS');
            console.log(location);
        }
    } else {
        console.log('too early to save');
    }
};


const getCurrentLatLon = () => new Promise(resolve => {
    navigator.geolocation.getCurrentPosition(
        info => resolve({
                lat: info.coords.latitude,
                lon: info.coords.longitude,
        }),
        () => resolve(null),
        {
            timeout: 100000,
            maximumAge: 0,
            enableHighAccuracy: true
        }
    );
});


BackgroundJob.register({
    jobKey: BACKGROUND_JOB_ID,
    job: () => {
        console.log("Running in background");
        saveLocation();
    }
});

export default class App extends React.Component {
    constructor(props) {
        super(props);

        this.mapTopLeft = this.mapBottomRight = null;

        this.region = {
            latitude: 37.78825,
            longitude: -122.4324,
            latitudeDelta: 0.015,
            longitudeDelta: 0.0121,
        };

        this.state = {
            region: this.region,
            pins: [],
        };

    }

    componentDidMount() {


        PushNotification.localNotification({
            /* Android Only Properties */
            /* iOS and Android properties */
            title: "My Notification Title", // (optional)
                message: "My Notification Message", // (required)
            playSound: false, // (optional) default: true
            soundName: 'default', // (optional) Sound to play when the notification is shown. Value of 'default' plays the default sound. It can be set to a custom sound such as 'android.resource://com.xyz/raw/my_sound'. It will look for the 'my_sound' audio file in 'res/raw' directory and play it. default: 'default' (default sound is played)
            number: '10', // (optional) Valid 32 bit integer specified as string. default: none (Cannot be zero)
    });






        PermissionsAndroid
            .request(PermissionsAndroid.PERMISSIONS.ACCESS_FINE_LOCATION)
            .then(res => {
                if (res === PermissionsAndroid.RESULTS.GRANTED) {
                    saveLocation();

                    BackgroundJob.schedule({
                        jobKey: BACKGROUND_JOB_ID,
                        period: 60000,
                        timeout: 10000,
                        allowExecutionInForeground: true
                    });

                    // BackgroundJob.isAppIgnoringBatteryOptimization(
                    //     (error, ignoringOptimization) => {
                    //         if (ignoringOptimization === true) {
                    //             BackgroundJob.schedule({
                    //                 jobKey: everRunningJobKey,
                    //                 period: 1000,
                    //                 exact: true,
                    //                 allowWhileIdle: true
                    //             });
                    //         } else {
                    //             console.log(
                    //                 "To ensure app functions properly,please manually remove app from battery optimization menu."
                    //             );
                    //             //Dispay a toast or alert to user indicating that the app needs to be removed from battery optimization list, for the job to get fired regularly
                    //         }
                    //     }
                    // );

                } else {
                    Alert.alert(
                        'Error',
                        'Please enable geo-location',
                        [{
                            text: 'OK',
                            onPress: () => {
                                console.log('OK Pressed')
                                BackHandler.exitApp();
                            }
                        }]
                    )
                }
            });
    }

  render() {
    return (
      <View style={styles.container}>

          <MapView
              style={styles.map}
              onRegionChange={this.onRegionChange.bind(this)}
              region={this.state.region}
          >

              {this.state.pins.map(pin =>
                  <Marker
                      key={pin.id}
                      title={
                          pin.type === 'place'
                              ? `${pin.createdAt}`
                              : `${pin.placesCount}`
                      }
                      pinColor={
                          pin.type === 'place'
                              ? '#FF2D00'
                              : '#0FFF00'
                      }
                      coordinate={{latitude: pin.lat, longitude: pin.lon}}
                  />
              )}

          </MapView>

      </View>
    );
  }

    onRegionChange(e) {
        this.region = e;

        this.mapTopLeft = {
            lat: e.latitude + e.latitudeDelta / 2,
            lon: e.longitude - e.longitudeDelta / 2
        };
        this.mapBottomRight = {
            lat: e.latitude - e.latitudeDelta / 2,
            lon: e.longitude + e.longitudeDelta / 2
        };

        this.reloadPins();

        // console.log(topLeft)
        // console.log(bottomRight)
    }

    reloadPins() {
        if (this.reloadPinsTimer) {
            clearTimeout(this.reloadPinsTimer);
        }

        if (this.reloadPinsCancelToken) {
            this.reloadPinsCancelToken();
        }

        this.reloadPinsTimer = setTimeout(() => {
            axios.get(
                `${URL}/in_square?`
                    + qs.stringify({
                        topLeft: this.mapTopLeft,
                        bottomRight: this.mapBottomRight,
                    }),
                    {
                        cancelToken: new (axios.CancelToken)(c => this.reloadPinsCancelToken = c)
                    }
                )
                .then(res => {
                    this.setState({
                        pins: res.data.items,
                        region: this.region
                    });
                    console.log('pins reloaded')
                })
                .catch(()  => {});
        }, 1000);
    }

}

const styles = StyleSheet.create({
    container: {
        ...StyleSheet.absoluteFillObject,
        flex: 1,
    },
    map: {
        ...StyleSheet.absoluteFillObject,
    },
  // container: {
  //   flex: 1,
  //   backgroundColor: '#fff',
  //   alignItems: 'center',
  //   justifyContent: 'center',
  // },
});

AppRegistry.registerComponent(appName, () => App);