import { Posts } from '@/Utils/Constance';
import Modal from './Modal';
import { useForm } from '@inertiajs/react';

export default function ModifyEmployeeModal({ show, onClose, selectedEmployee }) {
    const { data, setData, patch, processing, reset, errors } = useForm({
        nom: selectedEmployee?.user?.nom || '',
        prenom: selectedEmployee?.user?.prenom || '',
        telephone: selectedEmployee?.user?.telephone || '',
        post: selectedEmployee?.post || '',
        profilePicture: null, // Add this line to handle file upload
    });

    const modifyEmployee = (e) => {
        e.preventDefault();
        patch(route('employee.update', {employe: selectedEmployee }), {
            preserveScroll: true,
            onSuccess: () => {
                reset();
                onClose();
            },
        });
    };

    return (
        <Modal show={show} onClose={onClose}>
            <form onSubmit={modifyEmployee} className="p-4 text-center bg-white rounded-lg shadow dark:bg-gray-800">
                <h3 className="text-lg font-medium text-gray-900 dark:text-white">Modifier l'Employé</h3>

                {/* Post Selection */}
                <select
                    id="user_id"
                    name="user_id"
                    value={data.post}
                    onChange={(e) => setData('post', e.target.value)}
                    className="block w-full px-3 mb-4 py-2 border border-gray-300 mt-4 bg-white text-gray-900 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:placeholder-gray-400"
                >
                    <option value="" disabled>Sélectionner le poste</option>
                    {Posts.map(post => (
                        <option key={post.name} value={post.name}>
                            {post.name}
                        </option>
                    ))}
                </select>

                {/* Profile Picture Upload */}
                <input
                    type="file"
                    name="profilePicture"
                    accept="image/*"
                    onChange={(e) => setData('profilePicture', e.target.files[0])}
                    className="block w-full px-3 mb-4 py-2"
                />

                {/* Action Buttons */}
                <div className="flex justify-center items-center space-x-4">
                    <button type="button" onClick={onClose} className="py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100">Non, Annuler</button>
                    <button type="submit" disabled={processing} className="py-2 px-3 text-sm font-medium text-center text-white bg-green-600 rounded-lg hover:bg-green-700">
                        Oui, Modifier
                    </button>
                </div>
            </form>
        </Modal>
    );
}
