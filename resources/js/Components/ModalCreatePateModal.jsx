import Modal from './Modal';
import { useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function ModalCreateAffiliation({ show, onClose, users, compagnies }) {
    const { post, data, setData, reset, errors, processing } = useForm({
        user_id: '',
        compagnie_id: '',
        taux: '',
    });

    const [searchUser, setSearchUser] = useState('');
    const [searchCompagnie, setSearchCompagnie] = useState('');

    // Filter users based on search input
    const filteredUsers = users.filter(user =>
        `${user.nom} ${user.prenom}`.toLowerCase().includes(searchUser.toLowerCase())
    );

    // Filter companies based on search input
    const filteredCompagnies = compagnies.filter(compagnie =>
        compagnie.nom.toLowerCase().includes(searchCompagnie.toLowerCase())
    );

    if (!show) return null; // Modal is hidden if 'show' is false

    const handleCreateAffiliation = async (e) => {
        e.preventDefault();

        // Fetch the total existing commission rates for the selected company
        const totalResponse = await fetch(`/affiliations/total-taux/${data.compagnie_id}`);
        const totalData = await totalResponse.json();

        const totalTaux = totalData.total; // Assuming your API returns { total: number }

        // Check if the new commission rate exceeds the limit
        if (totalTaux + parseFloat(data.taux) > 50) {
            alert("La somme totale des taux d'affiliation ne doit pas dépasser 50%.");
            return;
        }

        // Use Inertia's post method for submission
        post(route('affiliation.store'), {
            onSuccess: () => {
                reset(); // Reset the form fields
                onClose(); // Close the modal on success
            },
        });
    };

    return (
        <Modal show={show} onClose={onClose}>
            <form onSubmit={handleCreateAffiliation} className="relative p-4 text-center bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                <button
                    type="button"
                    className="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    onClick={onClose}
                >
                    {/* Close icon */}
                </button>
                <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-4">Créer une nouvelle affiliation</h2>

                <div className="mb-4">
                    <label htmlFor="user_id" className="block text-sm font-medium text-gray-900 dark:text-white">Sélectionner un utilisateur</label>
                    <input
                        type="text"
                        placeholder="Rechercher un utilisateur"
                        value={searchUser}
                        onChange={(e) => setSearchUser(e.target.value)}
                        className="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:placeholder-gray-400"
                    />
                    <select
                        id="user_id"
                        name="user_id"
                        value={data.user_id}
                        onChange={(e) => setData('user_id', e.target.value)}
                        className="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:placeholder-gray-400"
                    >
                        <option value="" disabled>Sélectionner un utilisateur</option>
                        {filteredUsers.map(user => (
                            <option key={user.id} value={user.id}>{user.nom} {user.prenom}</option>
                        ))}
                    </select>
                    {errors.user_id && <div className="text-red-600 text-sm">{errors.user_id}</div>}
                </div>

                <div className="mb-4">
                    <label htmlFor="compagnie_id" className="block text-sm font-medium text-gray-900 dark:text-white">Sélectionner une compagnie</label>
                    <input
                        type="text"
                        placeholder="Rechercher une compagnie"
                        value={searchCompagnie}
                        onChange={(e) => setSearchCompagnie(e.target.value)}
                        className="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:placeholder-gray-400"
                    />
                    <select
                        id="compagnie_id"
                        name="compagnie_id"
                        value={data.compagnie_id}
                        onChange={(e) => setData('compagnie_id', e.target.value)}
                        className="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:placeholder-gray-400"
                    >
                        <option value="" disabled>Sélectionner une compagnie</option>
                        {filteredCompagnies.map(compagnie => (
                            <option key={compagnie.id} value={compagnie.id}>{compagnie.nom}</option>
                        ))}
                    </select>
                    {errors.compagnie_id && <div className="text-red-600 text-sm">{errors.compagnie_id}</div>}
                </div>

                <div className="mb-4">
                    <label htmlFor="taux" className="block text-sm font-medium text-gray-900 dark:text-white">Taux de commission (%)</label>
                    <input
                        type="number"
                        id="taux"
                        name='taux'
                        value={data.taux}
                        onChange={(e) => setData('taux', e.target.value)}
                        className="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:placeholder-gray-400"
                        placeholder="Entrez le taux de commission"
                        min="0"
                        max="50" // Ajustez le max pour définir la limite en pourcentage
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
                        disabled={processing} // Disable the button when processing
                    >
                        Créer l'affiliation
                    </button>
                </div>
            </form>
        </Modal>
    );
}
