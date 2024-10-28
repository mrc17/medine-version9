import Modal from './Modal';
import { useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function ModalEditAffiliation({ show, onClose, affiliation }) {
    console.log(affiliation)
    const { post, data, setData, reset, errors } = useForm({
       // taux: affiliation.taux, // Initialize with current taux
    });

    if (!show) return null; // Modal is hidden if 'show' is false

    const handleUpdateAffiliation = (e) => {
        e.preventDefault();
        post(route('affiliation.update', affiliation.id), {
            data: {
                taux: data.taux,
            },
            onSuccess: () => {
                reset();
                onClose();
            },
        });
    };

    return (
        <Modal show={show} onClose={onClose}>
            <form onSubmit={handleUpdateAffiliation} className="relative p-4 text-center bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                <button
                    type="button"
                    className="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    onClick={onClose}
                >
                    <svg aria-hidden="true" className="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fillRule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clipRule="evenodd"></path>
                    </svg>
                    <span className="sr-only">Fermer le modal</span>
                </button>
                <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-4">Modifier l'affiliation</h2>

                <div className="mb-4">
                    <label htmlFor="taux" className="block text-sm font-medium text-gray-900 dark:text-white">Taux de commission (%)</label>
                    <input
                        type="number"
                        id="taux"
                        name="taux"
                        value={data.taux}
                        onChange={(e) => setData('taux', e.target.value)}
                        className="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:placeholder-gray-400"
                        placeholder="Entrez le nouveau taux de commission"
                        min="0"
                        max="50" // Adjust max for the percentage limit
                    />
                    {errors.taux && <div className="text-red-600 text-sm">{errors.taux}</div>}
                </div>

                <div className="flex justify-center items-center space-x-4">
                    <button
                        type="button"
                        className="py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-gray-900 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600"
                        onClick={onClose}
                    >
                        Annuler
                    </button>
                    <button
                        type="submit"
                        className="py-2 px-3 text-sm font-medium bg-green-600 text-center text-white rounded-lg hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-900"
                    >
                        Mettre à jour
                    </button>
                </div>
            </form>
        </Modal>
    );
}
