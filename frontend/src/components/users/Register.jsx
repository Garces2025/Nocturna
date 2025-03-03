import axios from "axios";
import React, { useRef, useState } from "react";
import { useNavigate } from "react-router-dom";

const Register = () => {
    const [error, setError] = useState();
    const navigate = useNavigate();
    const data = useRef({name: '', last_name: '', birthday: '',email:'', password:''});

    const handleInput = (input) => {
        data.current[input.name] = input.value;
    }
    
    const handleRegister = async (event) => {
        event.preventDefault();
        const {name, last_name, birthday, email, password} = data.current;
        if(name && last_name && birthday && email && password) {
            try{
                const res = await axios.post('http://localhost:8000/api/users/register', data.current)
                if(res.data.exists){
                    setError('this email already exists in other account');
                }else{
                    alert('the account created successfully');
                    navigate('/');
                }
            }catch(e) {
                console.log(e.message);
            }
        }else{
            setError('all fields are required');
        }

    }

    return(
        <div style={{height: '100vh'}} className="content flex-column justify-content-center">
            <form onSubmit={(event)=>handleRegister(event)} method="post" style={{width: '450px'}} className="p-4 mt-1 mx-auto rounded bg-light">
            <h2 className="mx-2 p-1">Registro Usuario</h2>
                <p className="emptyError Error fw-semibold text-danger">{error}</p>
                <p className="fs-5 py-1 px-3">Bienvenidos a Popayán Nocturna, ¡Creemos una cuenta!</p>
                
                <div className="my-3 px-3">
                    <label>Nombre</label>
                    <input onChange={(event)=>handleInput(event.target)} type="text" name="name" className="email form-control px-3 col-12 att" />
                </div>

                <div className="my-3 px-3">
                    <label>Apellido</label>
                    <input onChange={(event)=>handleInput(event.target)} type="text" name="last_name" className="email form-control px-3 col-12 att" />
                </div>

                <div className="my-3 px-3">
                    <label>Cumpleaños</label>
                    <input onChange={(event)=>handleInput(event.target)} type="date" name="birthday" className="email form-control px-3 col-12 att" />
                </div>

                <div className="my-3 px-3">
                    <label>Correo electrónico</label>
                    <input onChange={(event)=>handleInput(event.target)} type="email" name="email" className="email form-control px-3 col-12 att" />
                </div>
                
                <div style={{position: 'relative'}} className="my-3 px-3">
                    <label>Contraseña</label>
                    <input onChange={(event)=>handleInput(event.target)} type="password" name="password" className="password att form-control ps-3 pe-5 col-12" />
                    {/* <i onclick="changevisiblity(this)" className="show-pass px-3 fa-solid fa-eye-slash"></i> */}
                </div>
    
                <div className="btnParent d-flex justify-content-end my-1 py-2 px-3">
                    <button style={{padding: '14px 0'}} type="submit" name="register" className="btn col-12 btn-dark rounded-1">Registrarse</button>
                </div>
            </form>
        </div>
    )
}

export default Register;