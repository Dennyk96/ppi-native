import * as React from 'react';
 


import './App.css';

import JqxDropDownList, { IDropDownListProps } from 'jqwidgets-scripts/jqwidgets-react-tsx/jqxdropdownlist';
import JqxPanel from 'jqwidgets-scripts/jqwidgets-react-tsx/jqxpanel';

class App extends React.PureComponent<{}, IDropDownListProps> {

    private myPanel = React.createRef<JqxPanel>();

    constructor(props: {}) {
        super(props);
        this.onSelect = this.onSelect.bind(this);
        this.onUnselect = this.onUnselect.bind(this);

        this.state = {
            source: [
                'Affogato',
                'Americano',
                'Bicerin',
                'Breve',
                'Café Bombón',
                'Café au lait',
                'Caffé Corretto',
                'Café Crema',
                'Caffé Latte',
                'Caffé macchiato',
                'Café mélange',
                'Coffee milk',
                'Cafe mocha',
                'Cappuccino',
                'Carajillo',
                'Cortado',
                'Cuban espresso',
                'Espresso',
                'Eiskaffee',
                'The Flat White',
                'Frappuccino',
                'Galao',
                'Greek frappé coffee',
                'Iced Coffee﻿',
                'Indian filter coffee',
                'Instant coffee',
                'Irish coffee',
                'Liqueur coffee'
            ]
        }
    }

    public render() {
        return (
            <div>
                <JqxDropDownList theme={'material-purple'} style={{ float: 'left', marginTop: '10px' }}
                    onSelect={this.onSelect} onUnselect={this.onUnselect}
                    width={200} height={30} source={this.state.source} selectedIndex={0} />
                <div style={{ float: 'left', marginLeft: '20px', marginTop: '10px' }}>
                    <span>Events:</span>
                    <JqxPanel theme={'material-purple'} ref={this.myPanel}
                        width={200} height={250} />
                </div>
            </div>
        );
    }

    private onSelect(event: any): void {
        const args = event.args;
        if (args !== undefined) {
            const item = event.args.item;
            if (item !== null) {
                this.myPanel.current!.prepend(`<div style="margin-top: 5px;">Selected: ${item.label}</div>`);
            }
        }
    }

    private onUnselect(event: any): void {
        const args = event.args;
        if (args !== undefined) {
            const item = event.args.item;
            if (item !== null) {
                this.myPanel.current!.prepend(`<div style="margin-top: 5px;">Unselected: ${item.label}</div>`);
            }
        }
    }
}

export default App;