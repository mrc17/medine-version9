import React from 'react';
import Modal from './Modal';
import { useForm } from '@inertiajs/react';
import InputError from './InputError';
import TextInput from './TextInput';
import InputLabel from './InputLabel';

function ModalUnclockUser({ show, onClose, utilisateur }) {
    if (!show) return null; // Return null for hidden modal

    const { put, processing } = useForm();

    const handleToggleBlock = (e) => {
        e.preventDefault();
        // Ici, vous devez décider comment bloquer ou débloquer l'utilisateur.
        // Vous pouvez ajouter une logique pour déterminer si l'utilisateur est actuellement bloqué ou non.
        const action = utilisateur.est_bloque ? 'debloquer' : 'bloquer'; // Ajustez selon votre logique
        put(route(`utilisateur.${action}`, { id: utilisateur.id }), {
            onSuccess: () => onClose(),
        });
    };

    return (
        <Modal show={show} onClose={onClose}>
            <div className="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                <button
                    onClick={onClose}
                    type="button"
                    className="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                >
                    <svg aria-hidden="true" className="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path
                            fillRule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clipRule="evenodd"
                        />
                    </svg>
                    <span className="sr-only">Fermer le modal</span>
                </button>
                <header className="mb-4">
                    <h2 className="text-2xl font-semibold text-white">{utilisateur.est_bloque ? "Débloquer l'utilisateur" : "Bloquer l'utilisateur"}</h2>
                    <p className="mt-1 text-sm text-gray-600">{utilisateur.est_bloque ? "Êtes-vous sûr de vouloir débloquer cet utilisateur ?" : "Êtes-vous sûr de vouloir bloquer cet utilisateur ?"}</p>
                </header>
                <form onSubmit={handleToggleBlock} className="mt-6 space-y-6">
                    <div>
                        <InputLabel className="text-left" value="Nom de l'utilisateur" />
                        <TextInput
                            id="nom"
                            value={utilisateur.nom}
                            readOnly // Rendre le champ en lecture seule, car il ne peut pas être modifié
                            className="mt-1 block w-full"
                        />
                    </div>
                    <div className="flex justify-center items-center space-x-4">
                        <button
                            onClick={onClose}
                            type="button"
                            className="py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
                        >
                            Non, annuler
                        </button>
                        <button
                            type="submit"
                            disabled={processing}
                            className="py-2 px-3 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900"
                        >
                            {processing ? (utilisateur.est_bloque ? "Déblocage..." : "Blocage...") : (utilisateur.est_bloque ? "Oui, débloquer" : "Oui, bloquer")}
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    );
}


export default ModalUnclockUser;

