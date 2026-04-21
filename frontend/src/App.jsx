import React, { Fragment } from 'react';
import { Routes } from "react-router";
import './App.css'
import PiePagina from './componentes/estructura/PiePagina.jsx';
import Cabecera from './componentes/estructura/Cabecera.jsx';
import Navegacion from './componentes/estructura/Navegacion.jsx'

function App() {

  return (
    <Fragment>
      <Routes>
        <Cabecera>
          <Navegacion/>
        </Cabecera>
        <PiePagina/>
      </Routes>
    </Fragment>
  );
}

export default App