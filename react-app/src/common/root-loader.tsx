import React, { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';

/**
 * loadEntry - A central function to mount a given component into a DOM element by ID.
 *
 * @param id - The DOM element ID (e.g., 'react-header')
 * @param Component - The React component to render
 */
export function RootLoader (
  id: string,
  Component: React.ComponentType<any>
): void {
  const el = document.getElementById(id);
  if(!el) return;
    
  return createRoot(el).render(
      <StrictMode>
        <Component />
      </StrictMode>
    );
}
