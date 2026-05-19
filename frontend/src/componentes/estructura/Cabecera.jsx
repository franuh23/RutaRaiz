import React, { Fragment } from "react";
import Navegacion from "./Navegacion";

const Cabecera = () => {
    return (
        <Fragment>
            <header>
                <h1>Mi biblioteca</h1>
                <Navegacion/>
            </header>
        </Fragment>
    );
};

export default Cabecera;