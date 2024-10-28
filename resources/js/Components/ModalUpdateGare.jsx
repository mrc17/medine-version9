import React from 'react';
import Modal from './Modal';
import { useForm } from '@inertiajs/react';
import InputError from './InputError';
import TextInput from './TextInput';
import InputLabel from './InputLabel';

function ModalUpdateGare({ show, onClose, gare }) {
    if (!show) return null; // Return null instead of false for React

    const { data, setData, errors, put, processing } = useForm({
        nom: gare.nom,
        ville: gare.ville,
        commune: gare.commune,
    });

    const updateGare = (e) => {
        e.preventDefault(); // Prevent default form submission
        put(route('gare.update', gare.id), { // Use gare.id for the route
            data: data, // Pass the data to the PUT request
            onSuccess: () => {
                onClose(); // Close modal on success
            },
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
                    <svg
                        aria-hidden="true"
                        className="w-5 h-5"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fillRule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clipRule="evenodd"
                        />
                    </svg>
                    <span className="sr-only">Fermer le modal</span>
                </button>
                <header className="mb-4">
                    <h2 className="text-2xl font-semibold text-white">Informations de gare</h2>
                    <p className="mt-1 text-sm text-gray-600">
                        Mettez à jour les informations de la gare.
                    </p>
                </header>
                <form onSubmit={updateGare} className="mt-6 space-y-6">
                    <div>
                        <InputLabel htmlFor="nom" className='text-left' value="Nom de la gare" />
                        <TextInput
                            id="nom"
                            value={data.nom}
                            onChange={(e) => setData('nom', e.target.value)}
                            type="text"
                            className="mt-1 block w-full"
                            autoComplete="nom"
                        />
                        <InputError message={errors.nom} className="mt-2" />
                    </div>
                    <div>
                        <InputLabel className='text-left' htmlFor="ville" value="Ville" />
                        <TextInput
                            id="ville"
                            value={data.ville}
                            onChange={(e) => setData('ville', e.target.value)}
                            type="text"
                            className="mt-1 block w-full"
                            autoComplete="ville"
                        />
                        <InputError message={errors.ville} className="mt-2" />
                    </div>
                    <div>
                        <InputLabel className='text-left' htmlFor="commune" value="Commune" />
                        <TextInput
                            id="commune"
                            value={data.commune}
                            onChange={(e) => setData('commune', e.target.value)}
                            type="text"
                            className="mt-1 block w-full"
                            autoComplete="commune"
                        />
                        <InputError message={errors.commune} className="mt-2" />
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
                            disabled={processing} // Disable button when processing
                            className="py-2 px-3 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900"
                        >
                            {processing ? "Mise à jour..." : "Oui, je suis sûr"} {/* Show processing text */}
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    );
}

export default ModalUpdateGare;
