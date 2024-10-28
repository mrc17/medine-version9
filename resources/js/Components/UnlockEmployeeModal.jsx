import Modal from './Modal';
import { useForm } from '@inertiajs/react';

export default function UnlockEmployeeModal({ show, onClose, selectedEmployee }) {
    const { patch, processing, reset, errors } = useForm();

    const unlockEmployee = (e) => {
        e.preventDefault();
        patch(route('employee.unlock', { employee: selectedEmployee }), {
            preserveScroll: true,
            onSuccess: () => {
                reset();
                onClose();
            },
        });
    };

    return (
        <Modal show={show} onClose={onClose}>
            <form onSubmit={unlockEmployee} className="p-4 text-center bg-white rounded-lg shadow dark:bg-gray-800">
                <h3 className="text-lg font-medium text-gray-900 dark:text-white">Débloquer l'Employé</h3>
                <p className="mb-4 text-gray-500 dark:text-gray-300">
                    Êtes-vous sûr de vouloir débloquer <strong>{selectedEmployee?.user?.nom}</strong> ?
                </p>
                <div className="flex justify-center items-center space-x-4">
                    <button type="button" onClick={onClose} className="py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100">Non, Annuler</button>
                    <button type="submit" disabled={processing} className="py-2 px-3 text-sm font-medium text-center text-white bg-green-600 rounded-lg hover:bg-green-700">
                        Oui, Débloquer
                    </button>
                </div>
            </form>
        </Modal>
    );
}
