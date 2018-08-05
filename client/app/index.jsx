import React from 'react';
import {render} from 'react-dom';
import { YMaps, Map, Placemark } from 'react-yandex-maps';
import axios from 'axios';
const qs = require('qs');

export class App extends React.Component {
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
            <div style={{'margin': '0 10px'}}>
                <YMaps>
                    <Map
                        state={{ center: this.mapCenter, zoom: 2 }}
                        width="1300px"
                        height="400px"
                        onBoundsChange={this.onBoundsChange.bind(this)}
                    >

                        {this.state.pins.map(pin =>
                            <Placemark
                                key={pin.id}
                                geometry={{
                                    coordinates: [pin.lat, pin.lon]
                                }}
                                properties={{
                                    hintContent: pin.type === 'place'
                                        ? pin.createdAt
                                        : pin.placesCount,
                                }}
                                options={
                                    pin.type !== 'place'
                                        ? {
                                            preset: 'islands#circleIcon',
                                            iconColor: '#3caa3c'
                                        }
                                        : {}
                                }
                            />
                        )}

                    </Map>
                </YMaps>
            </div>
        );
    }

    onBoundsChange(event) {
        this.mapCenter = event.originalEvent.map.getCenter();

        const [leftBottom, rightTop] = event.originalEvent.newBounds;

        this.mapTopLeft = {
            lat: rightTop[0],
            lon: leftBottom[1]
        };
        this.mapBottomRight = {
            lat: leftBottom[0],
            lon: rightTop[1]
        };

        this.reloadPins();
    }

    reloadPins() {
        if (this.reloadPinsTimer) {
            clearTimeout(this.reloadPinsTimer);
        }

        if (this.reloadPinsCancelToken) {
            this.reloadPinsCancelToken();
        }

        this.reloadPinsTimer = setTimeout(() => {
            axios
                .get(
                    '/api/places/in_square?' + qs.stringify({
                        topLeft: this.mapTopLeft,
                        bottomRight: this.mapBottomRight,
                    }),
                    {
                        cancelToken: new (axios.CancelToken)(c => this.reloadPinsCancelToken = c)
                    }
                )
                .then(res => this.setState({pins: res.data.items}))
                .catch(()  => {});
        }, 700);
    }
}

render(<App/>, document.getElementById('app'));