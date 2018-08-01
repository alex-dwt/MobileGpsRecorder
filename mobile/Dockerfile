FROM node

RUN echo "deb http://ftp.debian.org/debian jessie-backports main" >> /etc/apt/sources.list \
    && apt-get update \
    && apt-get install -y unzip usbutils \
    && apt-get install -y -t jessie-backports openjdk-8-jdk

WORKDIR /app
CMD ["bash", "/app/boot.bash"]

RUN cd /tmp \
    && wget https://dl.google.com/android/repository/sdk-tools-linux-4333796.zip \
    && mkdir -p /usr/local/android/platform-tools \
    && unzip sdk-tools-linux-4333796.zip -d /usr/local/android \
    && rm -f sdk-tools-linux-4333796.zip

RUN yes | /usr/local/android/tools/bin/sdkmanager --licenses

ENV ANDROID_HOME /usr/local/android
ENV PATH $PATH:/app/node_modules/.bin:/usr/local/android/platform-tools


#adb shell input keyevent 82
#adb reverse tcp:8081 tcp:8081

#react-native log-android
#react-native run-android

#mkdir android/app/src/main/assets
#react-native bundle --platform android --dev false --entry-file index.js --bundle-output android/app/src/main/assets/index.android.bundle --assets-dest android/app/src/main/res
#echo 'SUBSYSTEM=="usb", ATTR{idVendor}=="1004", MODE="0666", GROUP="plugdev"' | sudo tee /etc/udev/rules.d/51-android-usb.rules
#sudo adb kill-server
#sudo adb start-server
#udevadm control --reload-rules && udevadm trigger
#react-native bundle --platform android --dev false --entry-file index.js --bundle-output android/app/src/main/assets/index.android.bundle --assets-dest android/app/src/main/res/
