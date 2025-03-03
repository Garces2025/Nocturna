import React from "react";
import { NavLink } from "react-router-dom";

const DashboardLeftSide = () => {
    return(
        <div className="leftSide">
            <div>
                <nav>
                    <NavLink className='my-2' to="/admins/dashboard/users">Usuarios</NavLink>
                    <NavLink className='my-2' to="/admins/dashboard/events">Eventos</NavLink>
                    <NavLink className='my-2' to="/admins/dashboard/brands">Marcas</NavLink>
                    <NavLink className='my-2' to="/admins/dashboard/categories">Categorias</NavLink>
                </nav>
            </div>
        </div>
    )
}

export default DashboardLeftSide;