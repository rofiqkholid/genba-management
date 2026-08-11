import './bootstrap';
import '@fontsource/outfit/100.css';
import '@fontsource/outfit/200.css';
import '@fontsource/outfit/300.css';
import '@fontsource/outfit/400.css';
import '@fontsource/outfit/500.css';
import '@fontsource/outfit/600.css';
import '@fontsource/outfit/700.css';
import '@fontsource/outfit/800.css';
import '@fontsource/outfit/900.css';
import '@fortawesome/fontawesome-free/css/all.min.css';

import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);
window.Chart = Chart;

import Viewer from 'viewerjs';
import 'viewerjs/dist/viewer.css';
window.Viewer = Viewer;

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
