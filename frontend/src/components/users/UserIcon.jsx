import axios from "axios";
import Cookies from "js-cookie";
import React, { useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { NavLink, useNavigate } from "react-router-dom";

const UserIcon = () => {
    const isAuthenticated = useSelector(store => store.isAuthenticated);
    const user = useSelector(store => store.user);
    const dispatch = useDispatch();
    const navigate = useNavigate();
    const [displayDiv, setDisplayDiv] = useState(false);
    const routes = [
        {id:0, name:'Cuenta', route:'/users/account', isAuthenticated: true},
        {id:1, name:'Preferencias', route:'/savedEvents', isAuthenticated: true},
        {id:2, name:'Ingreso', route:'/users/login', isAuthenticated: false},
        {id:3, name:'Desconectarse', isAuthenticated: true},
        {id:4, name:'Registro', route:'/users/register', isAuthenticated: null}
    ]

    const handleLogOut = async () => {
        const confirmLogout = window.confirm('¿Estás seguro de que quieres cerrar sesión?');
        if(confirmLogout){
            try{
                await axios.post('http://localhost:8000/api/users/logout');
                dispatch( {type:'logout'} )
                Cookies.remove('token' ,{ sameSite: 'none', secure: true });
                localStorage.removeItem('user');
                navigate('/');
            }catch(err){
                console.log(err.message);
            }
        }
    }
    return(
        <div className="userIcon">
            {   !isAuthenticated
                ?   <i className="fa-solid fa-circle-user" onClick={()=>setDisplayDiv(displayDiv === false ? true : false)}></i>
                :   <img src={`/users_img/${user.profile.profilePic != null ? user.profile.profilePic : 'no-img.jpg'}`} alt="" onClick={()=>setDisplayDiv(displayDiv === false ? true : false)}/>
            }
            <div className={displayDiv===true ? 'open' : ''}>
                {routes.map( r =>
                    (isAuthenticated === r.isAuthenticated || r.isAuthenticated == null)
                    &&
                    (
                        r.name === 'Desconectarse'
                        ?   <span onClick={()=>handleLogOut()} key={r.id}>{r.name}</span>
                        :   <NavLink key={r.id} to={r.route} onClick={()=>setDisplayDiv(false)}>{r.name}</NavLink> 
                    ) 
                )}
            </div>
        </div>
    )
}

export default UserIcon;