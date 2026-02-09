import { startStimulusApp } from '@symfony/stimulus-bundle';

const app = startStimulusApp();
// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
// Register the editor controller used by ClasesType (data-controller="editor")
import EditorController from './controllers/editor_controller.js';
app.register('editor', EditorController);
