import React, { useState, useEffect } from 'react';
import { Link } from '@inertiajs/react';

function DemandeCreation() {
    const [demande, setDemande] = useState(0);

    const getNombreDemande = async () => {
        try {
            const response = await fetch('/compagnies/demandes/count');
            const data = await response.json();
            setDemande(data.count);
        } catch (error) {
            console.error("Erreur lors de la récupération du nombre de demandes:", error);
        }
    };

    useEffect(() => {
        const interval = setInterval(() => {
            getNombreDemande();
        }, 3000);

        return () => clearInterval(interval); // Clean up the interval on component unmount
    }, []);

    return (
        <li>
            <Link
                href={route("compagnies.demandes")}
                className="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
            >
                <svg
                    className="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path fillRule="evenodd" d="M4 3a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h1v2a1 1 0 0 0 1.707.707L9.414 13H15a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4Z" clipRule="evenodd" />
                    <path fillRule="evenodd" d="M8.023 17.215c.033-.03.066-.062.098-.094L10.243 15H15a3 3 0 0 0 3-3V8h2a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-1v2a1 1 0 0 1-1.707.707L14.586 18H9a1 1 0 0 1-.977-.785Z" clipRule="evenodd" />
                </svg>

                <span className="flex-1 ml-3 whitespace-nowrap">Demande</span>
                <span className="inline-flex justify-center items-center w-5 h-5 text-xs font-semibold rounded-full text-primary-800 bg-primary-100 dark:bg-primary-200 dark:text-primary-800">
                    {demande}
                </span>
            </Link>
        </li>
    );
}

export default DemandeCreation;
