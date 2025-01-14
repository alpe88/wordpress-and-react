import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import Header from './entries/header';
import Footer from './entries/footer';
import FrontPage from './entries/front-page';

// Access the localized data from WP
// This is the object you set in wp_localize_script.
declare global {
  interface Window {
    WPHeaderData?: any;
  }
}

// A simple map from DOM ID → React component
const mountPoints: Record<string, React.FC | React.ComponentType<any>> = {
  'react-header': Header,
  'react-footer': Footer,
  'react-front-page': FrontPage,
};

// Loop through each key in our dictionary and see if that ID exists
Object.entries(mountPoints).forEach(([domId, Component]) => {
  const el = document.getElementById(domId);
  if (el) {
    createRoot(el).render(
      <StrictMode>
        <Component />
      </StrictMode>
    );
  }
});