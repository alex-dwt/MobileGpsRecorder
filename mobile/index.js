import React from 'react';
import { StyleSheet, Text, View } from 'react-native';
import MapView from 'react-native-maps';
import { Marker } from 'react-native-maps';
import { AppRegistry } from "react-native";
import { name as appName } from "./app.json";

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

        console.log(topLeft)
        console.log(bottomRight)
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