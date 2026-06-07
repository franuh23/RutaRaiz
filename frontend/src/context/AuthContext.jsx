import React, { createContext, useState, useEffect, useContext } from 'react';
import { apiFetch } from '../services/api';
// Proveedor de autenticación, valida sesiones con apiFetch.

const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [token, setToken] = useState(localStorage.getItem('token') || null);
    const [loading, setLoading] = useState(true);

    const formatearUsuario = (usuarioObjeto) => {
        if (!usuarioObjeto) return null;
        const datosReales = usuarioObjeto.data ? usuarioObjeto.data : usuarioObjeto;
        const copiaUsuario = { ...datosReales };
        if (copiaUsuario.avatar && !copiaUsuario.avatar_url) {
            copiaUsuario.avatar_url = copiaUsuario.avatar;
        }
        return copiaUsuario;
    };

    useEffect(() => {
        const comprobarSesion = async () => {
            if (token) {
                try {
                    const response = await apiFetch('/api/usuario', {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });
                    if (response.ok) {
                        const usuarioData = await response.json();
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

    const login = async (email, password) => {
        const response = await apiFetch('/api/login', {
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
                await apiFetch('/api/logout', {
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