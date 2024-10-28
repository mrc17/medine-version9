import React from 'react';
import Modal from './Modal';
import TextInput from './TextInput';
import InputLabel from './InputLabel';
import InputError from './InputError';
import { useForm } from '@inertiajs/react';

const ModalAddUser = ({ show, onClose }) => {
    if (!show) return null; // Return null for hidden modal

    const { data, setData, errors, post, processing } = useForm({
        nom: '',
        prenom: '',
        telephone: '',
        password: '',
        password_confirmation: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('user.create')); // Call your route here
        onClose(); // Close the modal after submission
    };

    return (
        <Modal
            show={show}
            onClose={onClose}
            contentLabel="Ajouter un Utilisateur"
            overlayClassName="modal-overlay"
        >
            <div className="p-4 dark:bg-gray-800 bg-white dark:text-white text-gray-800">
                <h2 className="text-lg font-semibold">Ajouter un Utilisateur</h2>
                <form onSubmit={handleSubmit} className="mt-4">
                    <div className="mb-4">
                        <InputLabel htmlFor="nom" value="Nom" className='text-white' />
                        <TextInput
                            type="text"
                            id="nom"
                            value={data.nom}
                            onChange={(e) => setData('nom', e.target.value)}
                            required
                            className="mt-1 block w-full border dark:border-gray-600 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 bg-white"
                        />
                        <InputError message={errors.nom} className="mt-2" />
                    </div>

                    <div className="mb-4">
                        <InputLabel htmlFor="prenom" value="Prénom" className='text-white' />
                        <TextInput
                            type="text"
                            id="prenom"
                            value={data.prenom}
                            onChange={(e) => setData('prenom', e.target.value)}
                            required
                            className="mt-1 block w-full border dark:border-gray-600 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 bg-white"
                        />
                        <InputError message={errors.prenom} className="mt-2" />
                    </div>

                    <div className="mb-4">
                        <InputLabel htmlFor="telephone" value="Téléphone" className='text-white' />
                        <TextInput
                            type="tel"
                            id="telephone"
                            value={data.telephone}
                            onChange={(e) => setData('telephone', e.target.value)}
                            required
                            className="mt-1 block w-full border dark:border-gray-600 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 bg-white"
                        />
                        <InputError message={errors.telephone} className="mt-2" />
                    </div>

                    <div className="mb-4">
                        <InputLabel htmlFor="password" value="Mot de Passe" className='text-white' />
                        <TextInput
                            type="password"
                            id="password"
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            required
                            className="mt-1 block w-full border dark:border-gray-600 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 bg-white"
                        />
                        <InputError message={errors.password} className="mt-2" />
                    </div>

                    <div className="mb-4">
                        <InputLabel htmlFor="password_confirmation" value="Confirmer le Mot de Passe" className='text-white' />
                        <TextInput
                            type="password"
                            id="password_confirmation"
                            value={data.password_confirmation}
                            onChange={(e) => setData('password_confirmation', e.target.value)}
                            required
                            className="mt-1 block w-full border dark:border-gray-600 border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 bg-white"
                        />
                        <InputError message={errors.password_confirmation} className="mt-2" />
                    </div>

                    <div className="flex justify-end mt-4">
                        <button
                            type="button"
                            onClick={onClose}
                            className="mr-2 inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                        >
                            Annuler
                        </button>
                        <button
                            type="submit"
                            disabled={processing} // Disable the button while processing
                            className={`inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white ${processing ? 'bg-gray-600' : 'bg-blue-600 hover:bg-blue-700'} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500`}
                        >
                            {processing ? 'Ajout en cours...' : 'Ajouter'}
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    );
};

export default ModalAddUser;
