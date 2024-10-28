import React from 'react';

const StatCardProgress = ({ title, value, label }) => {
    const now = new Date();

    // Vérifier si la valeur est un tableau
    if (!Array.isArray(value)) {
        console.error("La valeur doit être un tableau d'objets.");
        return null; // Ou un affichage alternatif
    }

    // Calculer les éléments créés ce mois-ci
    const currentMonthItems = value.filter(item => {
        const createdAt = new Date(item.created_at);
        return createdAt.getMonth() === now.getMonth() && createdAt.getFullYear() === now.getFullYear();
    }).length;

    // Calculer les éléments créés le mois précédent
    const previousMonthItems = value.filter(item => {
        const createdAt = new Date(item.created_at);
        const previousMonthDate = new Date(now.getFullYear(), now.getMonth() - 1, 1); // Le mois précédent
        return createdAt.getMonth() === previousMonthDate.getMonth() && createdAt.getFullYear() === previousMonthDate.getFullYear();
    }).length;

    // Calculer le pourcentage de progression
    const calculateProgression = (current, previous) => {
        if (previous === 0) return 100; // Si aucune donnée précédente, on considère 100% d'augmentation
        return ((current - previous) / previous) * 100;
    };

    // Calculer la progression
    const percentage = calculateProgression(currentMonthItems, previousMonthItems).toFixed(2);

    // Choix de l'icône en fonction de la progression
    const icon = percentage >= 0 ? '⬆️' : '⬇️';

    return (
        <div className="bg-black p-4 sm:p-6 rounded-lg text-white shadow-md">
            <h3 className="text-sm sm:text-base font-medium">{title}</h3>
            <p className="text-lg sm:text-xl font-bold">{value.length}</p>
            <h3 className="text-sm sm:text-base font-medium mt-2">{label}</h3>
            <p className="text-lg sm:text-xl font-bold">{currentMonthItems}</p>
            <div className="flex items-center justify-between mt-2">
                <span className={`text-sm sm:text-base ${percentage >= 0 ? 'text-green-500' : 'text-red-500'}`}>
                    {icon} {percentage}%
                </span>
            </div>
        </div>
    );
};

export default StatCardProgress;
