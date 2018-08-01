import React from 'react';
import { StyleSheet, Text, View, Alert, PermissionsAndroid, BackHandler } from 'react-native';
import MapView from 'react-native-maps';
import { Marker } from 'react-native-maps';
import { AppRegistry } from "react-native";
import { name as appName } from "./app.json";
import BackgroundJob from 'react-native-background-job';

const BACKGROUND_JOB_ID = 'BACKGROUND_JOB';

let lastSavedPositionDate = null;

const saveLocation = async (force = false) => {
    console.log(lastSavedPositionDate);

    if (force
        || !lastSavedPositionDate
        || new Date(lastSavedPositionDate.getTime() + 2*60000) < new Date()
    ) {
        const location = await getCurrentLatLon();

        if (location) {
            lastSavedPositionDate = new Date();
            console.log('location saved success');
            console.log(location);
        } else {
            console.log('location NOT saved');
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
        this.mapCenter = [55.76, 37.64];

        this.state = {
            pins: [{
                lat: 29.899822162363883,
                lon: 131.33037108927965,
                id: 1,
                type: 'place',
                createdAt: '435353',
            }],
        };

    }

    componentDidMount() {
        PermissionsAndroid
            .request(PermissionsAndroid.PERMISSIONS.ACCESS_FINE_LOCATION)
            .then(res => {
                if (res === PermissionsAndroid.RESULTS.GRANTED) {
                    saveLocation();

                    BackgroundJob.schedule({
                        jobKey: BACKGROUND_JOB_ID,
                        period: 100000,
                        // timeout: 10000,
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
              region={{
                  latitude: 37.78825,
                  longitude: -122.4324,
                  latitudeDelta: 0.015,
                  longitudeDelta: 0.0121,
              }}
          >

              {this.state.pins.map(pin =>
                  <Marker
                      key={pin.id}
                      title={pin.createdAt}
                      coordinate={{latitude: pin.lat, longitude: pin.lon}}
                  />
              )}

          </MapView>

      </View>
    );
  }

    onRegionChange(e) {
        let topLeft = `${e.latitude + e.latitudeDelta / 2},${e.longitude - e.longitudeDelta / 2}`;
        let bottomRight = `${e.latitude - e.latitudeDelta / 2},${e.longitude + e.longitudeDelta / 2}`;

        // console.log(topLeft)
        // console.log(bottomRight)
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