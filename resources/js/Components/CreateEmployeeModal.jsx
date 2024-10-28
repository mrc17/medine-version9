import Modal from './Modal';
import { useState } from 'react';
import { Posts } from '@/Utils/Constance';
import { useForm } from '@inertiajs/react';

export default function CreateEmployeeModal({ show, onClose, users }) {
    const { data, setData, processing, post, reset } = useForm({
        login: "",
        password: "",
        post: "",
        path: "",
        adresse: "",
        user_id: "",
        carteIdentite: null,
    });

    const [searchUser, setSearchUser] = useState('');

    const filteredUsers = users.filter(user =>
        user.nom.toLowerCase().includes(searchUser.toLowerCase()) ||
        user.prenom.toLowerCase().includes(searchUser.toLowerCase())
    );

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('employe.store'), {
            onSuccess: () => {
                reset();
                onClose();
            },
        });
    };

    return (
        <Modal show={show} onClose={onClose}>
            <div className="p-4 text-center bg-white rounded-lg shadow dark:bg-gray-800">
                <h3 className="text-lg font-medium text-gray-900 dark:text-white">Créer un Nouvel Employé</h3>
                <form onSubmit={handleSubmit} className="mt-4 space-y-4">
                    <div className="mb-4">
                        <label htmlFor="user_id" className="block text-sm font-medium text-gray-900 dark:text-white">
                            Sélectionner un utilisateur
                        </label>
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
                            className="block w-full px-3 py-2 border border-gray-300 mt-4 bg-white text-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:placeholder-gray-400"
                        >
                            <option value="" disabled>Sélectionner un utilisateur</option>
                            {filteredUsers.map(user => (
                                <option key={user.id} value={user.id}>
                                    {user.nom} {user.prenom}
                                </option>
                            ))}
                        </select>
                    </div>

                    <input
                        type="text"
                        placeholder="Adresse"
                        value={data.adresse}
                        onChange={(e) => setData('adresse', e.target.value)}
                        className="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:placeholder-gray-400"
                        required
                    />

                    <select
                        id="post"
                        name="post"
                        value={data.post}
                        onChange={(e) => setData('post', e.target.value)}
                        className="block w-full px-3 py-2 border border-gray-300 mt-4 bg-white text-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:placeholder-gray-400"
                    >
                        <option value="" disabled>Sélectionner le poste</option>
                        {Posts.map(post => (
                            <option key={post.name} value={post.name}>
                                {post.name}
                            </option>
                        ))}
                    </select>

                    <input
                        className="block w-full text-sm text-gray-900 border p-2 border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        aria-describedby="user_avatar_help"
                        id="user_avatar"
                        type="file"
                        accept="application/image/png"
                        onChange={(e) => setData('carteIdentite', e.target.files[0])}
                    />
                    <div className="mt-1 text-sm text-gray-500 dark:text-gray-300" id="user_avatar_help">
                        Une photo de profil est utile pour confirmer que vous êtes connecté à votre compte
                    </div>

                    <input
                        type="text"
                        placeholder="Login"
                        value={data.login}
                        onChange={(e) => setData('login', e.target.value)}
                        className="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:placeholder-gray-400"
                        required
                    />
                    <input
                        type="password"
                        placeholder="Mot de passe"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        className="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:placeholder-gray-400"
                        required
                    />

                    <div className="mt-4">
                        <button
                            type="submit"
                            className="py-2 px-4 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300"
                        >
                            Créer Employé
                        </button>
                        <button
                            type="button"
                            onClick={onClose}
                            className="ml-2 py-2 px-4 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600"
                        >
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    );
}
