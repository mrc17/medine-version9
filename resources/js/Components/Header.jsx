import "../../css/style.css"
import { useState } from 'react';
import { Link } from "@inertiajs/react";
import ApplicationLogo from "./ApplicationLogo";
import '../../css/style.css'

export default function Header({ auth }) {
    const [isMenuOpen, setIsMenuOpen] = useState(false);

    const toggleMenu = () => {
        setIsMenuOpen(!isMenuOpen);
    };

    return (
        <header>
            <nav className="border-white-200 px-4 fixed w-full top-0 lg:px-6 py-2.5 secondary">
                <div className="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
                    {/* Lien vers l'accueil ou votre propre logo */}
                    <Link href="/" className="flex items-center">
                        <ApplicationLogo alt="Logo" />
                        <span className="self-end text-xl font-bold whitespace-nowrap  dark:text-white uppercase text-primary">edine</span>
                    </Link>

                    <div className="flex items-center lg:order-2">
                        {auth?.nom ? (
                            <Link href={route('dashboard')} className="font-semibold text-black hover:text-white dark:text-white-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                Tableau de bord
                            </Link>
                        ) : (
                            <>
                                <Link href={route('login')} className="font-semibold text-black hover:text-white dark:text-white-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                    Se connecter
                                </Link>
                            </>
                        )}
                        {/* Bouton pour ouvrir/fermer le menu mobile */}
                        <button
                            type="button"
                            className="inline-flex items-center p-2 ml-1 text-sm text-white-500 rounded-lg lg:hidden hover:bg-white-100 focus:outline-none focus:ring-2 focus:ring-white-200 dark:text-white-400 dark:hover:bg-white-700 dark:focus:ring-white-600"
                            aria-controls="mobile-menu-2"
                            aria-expanded={isMenuOpen ? "true" : "false"}
                            onClick={toggleMenu}
                        >
                            <span className="sr-only">Ouvrir le menu principal</span>
                            {isMenuOpen ? (
                                <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fillRule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clipRule="evenodd"></path>
                                </svg>
                            ) : (
                                <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fillRule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clipRule="evenodd"></path>
                                </svg>
                            )}
                        </button>
                    </div>

                    {/* Menu de navigation mobile */}
                    <div className={`${isMenuOpen ? 'block' : 'hidden'} justify-between items-center w-full lg:flex lg:w-auto lg:order-1`} id="mobile-menu-2">
                        <ul className="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
                            <li>
                                <a href="#accueil" className="block py-2 pr-4 pl-3 text-black hover:text-white bg-primary-700 rounded lg:bg-transparent lg:text-primary-700 lg:p-0">
                                    Accueil
                                </a>
                            </li>
                            <li>
                                <a href="#entreprises" className="block py-2 pr-4 pl-3 text-black hover:text-white border-b border-white-100 hover:bg-white-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-white-400 lg:dark:hover:text-white dark:hover:bg-white-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-white-700">
                                    Compagnies
                                </a>
                            </li>
                            <li>
                                <a href="#fonctionnalites" className="block py-2 pr-4 pl-3 text-black hover:text-white border-b border-white-100 hover:bg-white-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-white-400 lg:dark:hover:text-white dark:hover:bg-white-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-white-700">
                                    Démo
                                </a>
                            </li>
                            <li>
                                <a href="#contact" className="block py-2 pr-4 pl-3 text-black hover:text-white border-b border-white-100 hover:bg-white-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-white-400 lg:dark:hover:text-white dark:hover:bg-white-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-white-700">
                                    Contact
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
    );
}
