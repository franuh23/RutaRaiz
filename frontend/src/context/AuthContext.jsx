import React, { createContext, useState, useEffect, useContext } from 'react';

const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [token, setToken] = useState(localStorage.getItem('token') || null);
    const [loading, setLoading] = useState(true);

    // Validar si hay una sesión activa al cargar la app
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
                        setUser(usuarioData);
                    } else {
                        logout();
                    }
                } catch (error) {
                    console.error("Error al validar la sesión:", error);
                    logout();
                }
            }
            setLoading(false);
        };

        comprobarSesion();
    }, [token]);

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

        localStorage.setItem('token', data.access_token);
        setToken(data.access_token);
        setUser(data.user);
        return data.user;
    };

    // Función para Cerrar Sesión
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
        localStorage.removeItem('token');
        setToken(null);
        setUser(null);
    };

    return (
        <AuthContext.Provider value={{ user, token, login, logout, isAuthenticated: !!user, loading }}>
            {children}
        </AuthContext.Provider>
    );
};

export const useAuth = () => useContext(AuthContext);