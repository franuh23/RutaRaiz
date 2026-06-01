import React, { createContext, useState, useEffect, useContext } from 'react';

const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [token, setToken] = useState(localStorage.getItem('token') || null);
    const [loading, setLoading] = useState(true);

    // 🚀 Función corregida para respetar el Base64 puro directo de Neon
    const formatearUsuario = (usuarioObjeto) => {
        if (!usuarioObjeto) return null;
        
        // Si lo que nos llega es la respuesta directa de /api/usuario, extraemos los datos limpios
        const datosReales = usuarioObjeto.data ? usuarioObjeto.data : usuarioObjeto;
        
        const copiaUsuario = { ...datosReales };

        // Aseguramos que la propiedad avatar_url contenga el Base64 limpio si no venía ya mapeada
        if (copiaUsuario.avatar && !copiaUsuario.avatar_url) {
            copiaUsuario.avatar_url = copiaUsuario.avatar;
        }
        
        return copiaUsuario;
    };

    // Validar si hay una sesión activa al arrancar la app
    useEffect(() => {
        const comprobarSesion = async () => {
            if (token) {
                try {
                    const response = await fetch('/api/usuario', {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        const usuarioData = await response.json();
                        // Guardamos asegurando el formateo del avatar
                        setUser(formatearUsuario(usuarioData));
                    } else if (response.status === 401) {
                        ejecutarLimpiezaLocal();
                    }
                } catch (error) {
                    console.error("Error de conexión al validar la sesión:", error);
                }
            }
            setLoading(false);
        };

        comprobarSesion();
    }, []);

    // Función para Iniciar Sesión
    const login = async (email, password) => {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Error al iniciar sesión');
        }

        // Formateamos el usuario antes de guardarlo en el estado global
        const usuarioProcesado = formatearUsuario(data.user);

        localStorage.setItem('token', data.access_token);
        setToken(data.access_token);
        setUser(usuarioProcesado);
        return usuarioProcesado;
    };

    const ejecutarLimpiezaLocal = () => {
        localStorage.removeItem('token');
        setToken(null);
        setUser(null);
    };

    const logout = async () => {
        if (token) {
            try {
                await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
            } catch (error) {
                console.error("Error al cerrar sesión en el servidor:", error);
            }
        }
        ejecutarLimpiezaLocal();
    };

    return (
        <AuthContext.Provider value={{ user, setUser, token, login, logout, isAuthenticated: !!user, loading }}>
            {children}
        </AuthContext.Provider>
    );
};

export const useAuth = () => useContext(AuthContext);