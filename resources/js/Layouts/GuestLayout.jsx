import ApplicationLogo from '@/Components/ApplicationLogo';
import { Link } from '@inertiajs/react';
import "./../../css/style.css"

export default function Guest({ children }) {
    return (
        <div className="grid lg:grid-cols-6 min-h-screen">
            <div className="hidden md:flex flex-col bg-[url('/images/asset/bg.png')] bg-cover bg-no-repeat bg-center  col-span-3">

                <div className='flex flex-col h-full items-center justify-center p-8 bg-slate-900/80'>
                    <div className="flex items-center justify-center">
                        <img className="h-56 w-56 " src="../images/asset/android-chrome-192x192.png" alt="Logo" />
                    </div>
                    <h2 className='font-bold text-3xl text-white mt-4'>Bienvenue dans l'espace de gestion Médine.</h2>
                </div>
            </div>
            {/* Main Content Area */}
            <div className="col-span-3 flex justify-center items-center  p-8 primary">
                <div className="w-full max-w-md">
                    {children}
                </div>
            </div>
        </div>
    );
}
