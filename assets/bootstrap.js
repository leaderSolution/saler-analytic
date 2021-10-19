import { startStimulusApp } from '@symfony/stimulus-bridge';
// import { Autocomplete } from 'stimulus-autocomplete';
import { Autocomplete } from '@symfony/stimulus-bridge/lazy-controller-loader?lazy=true&export=Autocomplete!stimulus-autocomplete';

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.(j|t)sx?$/
));
app.register('autocomplete', Autocomplete);
// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
