import React from 'react';
import { StyleSheet, Text, View } from 'react-native';

export default class App extends React.Component {
    constructor(props) {
        super(props);

        this.mapTopLeft = this.mapBottomRight = null;
        this.mapCenter = [55.76, 37.64];

        this.state = {
            pins: [],
        };
    }

  render() {
    return (
      <View style={styles.container}>
        <Text>TEEEEXT111</Text>

{/*          <YMaps>
              <Map
                  state={{ center: this.mapCenter, zoom: 2 }}
                  width="1300px"
                  height="400px"
              >
              </Map>
          </YMaps>*/}

      </View>
    );
  }

}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    alignItems: 'center',
    justifyContent: 'center',
  },
});
