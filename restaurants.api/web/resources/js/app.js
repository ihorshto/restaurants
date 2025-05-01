import './bootstrap';
import './vendor/preline.min.js';

import Alpine from 'alpinejs';
import 'preline';
import _ from 'lodash';
import Dropzone from 'dropzone';

window.Alpine = Alpine;
window._ = _;
window.Dropzone = Dropzone;

Alpine.start();
