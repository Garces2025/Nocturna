import React, { useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { NavLink, useNavigate } from "react-router-dom";
import api from '../../axios/axios';
import Cookies from "js-cookie";

const UserIcon = () => {
    const isAuthenticated = useSelector(store => store.isAuthenticated);
    const user = useSelector(store => store.user);
    const dispatch = useDispatch();
    const navigate = useNavigate();
    const [displayDiv, setDisplayDiv] = useState(false);
    const routes = [
        {id:0, name:'Cuenta', route:'/users/account', isAuthenticated: true, tooltip: 'Gestiona tu perfil y configuración'},
        {id:1, name:'Preferencias', route:'/savedEvents', isAuthenticated: true, tooltip: 'Ver tus eventos guardados'},
        {id:2, name:'Ingreso', route:'/users/login', isAuthenticated: false, tooltip: 'Inicia sesión en tu cuenta'},
        {id:3, name:'Desconectarse', isAuthenticated: true, tooltip: 'Cerrar sesión'},
        {id:4, name:'Registro', route:'/users/register', isAuthenticated: null, tooltip: 'Crear una nueva cuenta'}
    ];

    const handleLogOut = async () => {
        const confirmLogout = window.confirm('¿Estás seguro de que quieres cerrar sesión?');
        if(confirmLogout){
            try {
                await api.post('/api/users/logout');
                dispatch({type:'logout'});
                Cookies.remove('token', { path: '/' });
                localStorage.removeItem('user');
                navigate('/users/login');
            } catch (error) {
                console.error('Error al cerrar sesión:', error);
            }
        }
    };

    return(
        <div className="userIcon">
            {!isAuthenticated
                ? <i className="fa-solid fa-circle-user" onClick={()=>setDisplayDiv(!displayDiv)} title="Opciones de cuenta"></i>
                : <img src="/users_img/no-img.jpg" alt="" onClick={()=>setDisplayDiv(!displayDiv)} title="Tu perfil"/>
            }
            <div className={displayDiv ? 'open' : ''}>
                {routes.map(r =>
                    (isAuthenticated === r.isAuthenticated || r.isAuthenticated == null)
                    && (
                        r.name === 'Desconectarse'
                        ? <span onClick={handleLogOut} key={r.id} title={r.tooltip}>{r.name}</span>
                        : <NavLink key={r.id} to={r.route} onClick={()=>setDisplayDiv(false)} title={r.tooltip}>{r.name}</NavLink> 
                    ) 
                )}
            </div>
        </div>
    );
};

export default UserIcon;