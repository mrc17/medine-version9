import React from 'react';

const StatCard = ({ title, value, percentage }) => (
    <div className="bg-black p-4 sm:p-6 rounded-lg text-white shadow-md">
        <h3 className="text-sm sm:text-lg font-medium">{title}</h3>
        <p className="text-xl sm:text-2xl font-bold">{value}</p>
        <p className={`text-xs sm:text-sm ${percentage >= 0 ? 'text-green-500' : 'text-red-500'}`}>
            {percentage}%
        </p>
    </div>
);

export default StatCard;
