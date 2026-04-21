import React, {Fragment} from "react";
import { NavLink } from "react-router-dom";

const Navegacion = () => {
    return (
        <Fragment>
            <nav>
                <ul>
                    <li className="menu_item">
                        <NavLink to="/"
                            className={({isActive}) => isActive ? `menu_link menu_link--activo` : `menu_link`}>Rutas
                        </NavLink>
                    </li>

                    <li className="menu_item">
                        <NavLink to="/crear"
                            className={({isActive}) => isActive ? `menu_link menu_link--activo` : `menu_link`}>Planificador
                        </NavLink>
                    </li>

                    <li className="menu_item">
                        <NavLink to="/crear"
                            className={({isActive}) => isActive ? `menu_link menu_link--activo` : `menu_link`}>Ranking
                        </NavLink>
                    </li>

                    <li className="menu_item">
                        <NavLink to="/crear"
                            className={({isActive}) => isActive ? `menu_link menu_link--activo` : `menu_link`}>Iniciar sesión
                        </NavLink>
                    </li>

                    <li className="menu_item">
                        <NavLink to="/crear"
                            className={({isActive}) => isActive ? `menu_link menu_link--activo` : `menu_link`}>Registro
                        </NavLink>
                    </li>
                </ul>
            </nav>
        </Fragment>
    );
};

export default Navegacion;