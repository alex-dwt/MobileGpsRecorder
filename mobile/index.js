import React from 'react';
import { StyleSheet, Text, View } from 'react-native';
import MapView from 'react-native-maps';

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

          {/*<MapView*/}
              {/*initialRegion={{*/}
                  {/*latitude: 37.78825,*/}
                  {/*longitude: -122.4324,*/}
                  {/*latitudeDelta: 0.0922,*/}
                  {/*longitudeDelta: 0.0421,*/}
              {/*}}*/}
          {/*/>*/}

        <Text>$$$$$$$$$$$</Text>


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
    // AIzaSyAMfUAie_tKpYmtQsbOK1R0NB_3onBOBVM
    // apt-get install  usbutils
    // apt-get install -y unzip
    // wget https://dl.google.com/android/repository/platform-tools-latest-linux.zip

    // adb devices

    // export JAVA_HOME=/usr/lib/jvm/java-1.7.0-openjdk-amd64/
    // export  PATH="$PATH:$JAVA_HOME/bin"

    // apt-get install default-jdk

// ./node_modules/.bin/react-native eject


    // Updating certificates in /etc/ssl/certs... 0 added, 0 removed; done.
    // Running hooks in /etc/ca-certificates/update.d....
// /etc/ca-certificates/update.d/jks-keystore: 82: /etc/ca-certificates/update.d/jks-keystore: java: not found
//     E: /etc/ca-certificates/update.d/jks-keystore exited with code 1.
//     done.

// /tmp/platform-tools/adb reverse tcp:8081 tcp:8081

    // echo deb http://ftp.debian.org/debian jessie-backports main >> /etc/apt/sources.list

    // apt-get install -t jessie-backports openjdk-8-jdk

    // export ANDROID_HOME=/usr/local/android
    // export PATH=$PATH:$ANDROID_HOME/tools
    // export PATH=$PATH:$ANDROID_HOME

    // yes | /usr/local/android/tools/bin/sdkmanager --licenses

    // wget https://dl.google.com/android/repository/sdk-tools-linux-4333796.zip
    // unzip /usr/local/ sdk-tools-linux-4333796.zip


    // mkdir android/app/src/main/assets
    // react-native bundle --platform android --dev false --entry-file index.js --bundle-output android/app/src/main/assets/index.android.bundle --assets-dest android/app/src/main/res

    // echo 'SUBSYSTEM=="usb", ATTR{idVendor}=="1004", MODE="0666", GROUP="plugdev"' | sudo tee /etc/udev/rules.d/51-android-usb.rules
    // sudo adb kill-server
    // sudo adb start-server
// # udevadm control --reload-rules && udevadm trigger

    // react-native bundle --platform android --dev false --entry-file index.js --bundle-output android/app/src/main/assets/index.android.bundle --assets-dest android/app/src/main/res/

}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    alignItems: 'center',
    justifyContent: 'center',
  },
});


import { AppRegistry } from "react-native";
// import App from "./App";
import { name as appName } from "./app.json";
AppRegistry.registerComponent(appName, () => App);