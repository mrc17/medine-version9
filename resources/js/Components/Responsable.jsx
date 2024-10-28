import React from 'react';
import { Link } from '@inertiajs/react';

const Responsable = ({ user }) => {
    // Default user object to handle undefined user
    const defaultUser = {
        nom: 'Nom Inconnu',
        telephone: 'Téléphone Inconnu',
        avatar: 'https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/bonnie-green.png',
    };

    const { nom, telephone, avatar } = user || defaultUser;

    return (
        <div className="grid grid-cols-1 md:grid-cols-1 mt-4 lg:grid-cols-3 gap-3">
            <div className="flex-col justify-center items-center">
                <h3 className="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                    <Link href="#">{nom}</Link>
                </h3>
                <span className="text-gray-500 dark:text-gray-400">Responsable compagnie</span>
                <p className="mt-3 mb-4 font-light text-gray-500 dark:text-gray-400">
                    {telephone}
                </p>
            </div>
            <div className="flex justify-center shadow-md bg-gray-600 p-3 items-center">
                {/* Carte  indentite verso */}
                <img
                    className="h-auto w-full rounded-lg sm:rounded-none sm:rounded-l-lg"
                    src={'/images/asset/image.png'} // Use the user's avatar or default one
                    alt={`${nom}'s avatar`}
                />
            </div>
            <div className="flex justify-center shadow-md bg-gray-600 p-3 items-center">
                {/* Carte  indentite rerto */}
                <img
                    className="h-auto w-full rounded-lg sm:rounded-none sm:rounded-l-lg"
                    src={'/images/asset/image.png'} // Placeholder image, adjust as needed
                    alt="Placeholder"
                />
            </div>

        </div>
    );
};

export default Responsable;
