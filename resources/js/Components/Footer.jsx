import { Link } from '@inertiajs/react'
import React from 'react'

function Footer() {
    return (
        <footer id='contact' className="bg-[url('/images/asset/bg.png')]">
            <div className='bg-slate-900/80'>
                <div className="mx-auto w-full max-w-screen-xl p-2">
                    <div className="md:flex md:justify-between">
                        <div className="flex items-center justify-center">
                            <a href="/" className="flex items-center">
                                <img className="h-10 w-10 " src="../images/asset/android-chrome-192x192.png" alt="Logo" />
                                <span className="self-end text-2xl font-bold whitespace-nowrap uppercase text-color">edine</span>
                            </a>
                        </div>
                        <div className="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                            <div>
                                <h2 className="mb-1 text-sm font-semibold text-gray-900 uppercase dark:text-white underline">Ressources</h2>
                                <ul className="font-medium dark:text-gray-400">
                                    <li className="mb-1 text-white">
                                        <a href="#accueil" className="hover:underline">Accueil</a>
                                    </li>
                                    <li className="mb-1 text-white">
                                        <a href="#entreprises" className="hover:underline">Compagnies</a>
                                    </li>
                                    <li className="mb-1 text-white">
                                        <a href="#contact" className="hover:underline">
                                            <span> +225 07 69 19 45 84</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h2 className="mb-1 text-sm font-semibold text-gray-900 uppercase dark:text-white underline">Suivez-nous</h2>
                                <ul className="text-gray-500 text-wrap flex-wrap dark:text-gray-400 font-medium">
                                    <li className="mb-1 text-white">
                                        <a href="#fonctionnalites" className="hover:underline">Démo</a>
                                    </li>
                                    <li className="mb-1 text-white">
                                        <a href="#contact" className="hover:underline">
                                            <span> Abidjan, Côte d'ivoire</span>
                                        </a>
                                    </li>
                                    <li className="mb-1 text-white">
                                        <a href="#contact" className="hover:underline">
                                            <span> +225 07 57 34 65 65</span>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                            <div>
                                <h2 className="mb-1 text-sm font-semibold text-gray-900 uppercase dark:text-white underline">Légal</h2>
                                <ul className="text-gray-500 dark:text-gray-400 font-medium">
                                    <li className="mb-1 text-white">
                                        <Link href={route("conditions")} className="hover:underline">Conditions Générales</Link>
                                    </li>
                                    <li className="mb-1 text-white">
                                        <Link href={route("politics")} className="hover:underline">Politique de Confidentialité</Link>
                                    </li>
                                    <li className="mb-1 text-white">

                                        <a href="#contact" className="hover:underline">
                                            <span> contacts@cybernexservices.com</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <hr className="my-2 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-2" />
                    <div className="sm:flex sm:items-center items-center justify-center">
                        <p className="text-sm text-gray-500 text-center dark:text-gray-400">By CyberNex Services © {new Date().getFullYear()} Medine. Tous droits réservés.</p>
                    </div>
                </div>
            </div>
        </footer>
    )
}

export default Footer
