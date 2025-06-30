//index.js

import React from 'react';
import { createRoot } from 'react-dom/client'; // Corrected import for React 18
import './index.css';
import App from './App';
import reportWebVitals from './reportWebVitals';
import { BrowserRouter } from 'react-router-dom';

// Find the root div in your HTML
const rootElement = document.getElementById('root');
const root = createRoot(rootElement);  // Create a root.

// Render your application
root.render(
  <React.StrictMode>
      <App />
  </React.StrictMode>
);
reportWebVitals();
