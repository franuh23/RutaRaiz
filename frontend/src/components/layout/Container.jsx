import React from 'react';

export default function Container({ children, className = '' }) {
  return (
    <div className={`container-xl ${className}`}>
      {children}
    </div>
  );
}