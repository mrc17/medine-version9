import React, { useState } from 'react';
import { Doughnut, Line } from 'react-chartjs-2';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    ArcElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js';

// Register all necessary Chart.js components
ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    ArcElement,
    Title,
    Tooltip,
    Legend
);

// Function to filter tickets/users by period
const filterByPeriod = (data, period) => {
    const now = new Date();
    const periods = {
        jour: now.setDate(now.getDate() - 1),
        semaine: now.setDate(now.getDate() - 7),
        mois: now.setMonth(now.getMonth() - 1),
        '3months': now.setMonth(now.getMonth() - 3),
        '6months': now.setMonth(now.getMonth() - 6),
        année: now.setFullYear(now.getFullYear() - 1),
        all: 0 // All data, no filtering
    };

    return data.filter(item => {
        const createdAt = new Date(item.created_at);
        return createdAt >= (period === 'all' ? new Date(0) : new Date(periods[period]));
    });
};

// Function to generate labels based on period
const generateLabels = (period) => {
    const labels = [];
    const now = new Date();
    switch (period) {
        case 'jour':
            for (let i = 0; i < 24; i++) {
                labels.push(`${i}h`);
            }
            break;
        case 'semaine':
            labels.push(...['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche']);
            break;
        case 'mois':
            for (let i = 1; i <= 31; i++) {
                labels.push(i.toString());
            }
            break;
        case '3months':
        case '6months':
        case 'année':
            for (let i = 0; i < (period === 'année' ? 12 : 3); i++) {
                const date = new Date();
                date.setMonth(date.getMonth() - i);
                labels.push(date.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' }));
            }
            labels.reverse();
            break;
        default:
            break;
    }
    return labels;
};

// Function to generate chart data
const generateChartData = (filteredData, period, isTicket) => {
    const dataGrouped = filteredData.reduce((acc, item) => {
        const date = new Date(item.created_at);
        let label;

        switch (period) {
            case 'jour':
                label = `${date.getHours()}h`;
                break;
            case 'semaine':
                label = date.toLocaleDateString('fr-FR', { weekday: 'long' });
                break;
            case 'mois':
                label = date.getDate().toString();
                break;
            case '3months':
            case '6months':
            case 'année':
                label = date.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
                break;
            case 'all':
                label = date.getFullYear().toString();
                break;
            default:
                label = '';
        }

        acc[label] = (acc[label] || 0) + 1;
        return acc;
    }, {});

    const labels = generateLabels(period);
    const data = labels.map(label => dataGrouped[label] || 0);

    return {
        labels: labels,
        datasets: [
            {
                label: isTicket ? 'Tickets Vendus' : 'Utilisateurs Inscrits',
                data: data,
                fill: true,
                backgroundColor: 'rgba(2, 102, 255, 0.2)',
                borderColor: 'rgba(2, 102, 255, 1)',
                pointRadius: data.map(count => (count > 0 ? 5 : 0)),
            }
        ],
    };
};

// Common chart options
const revenueOptions = {
    responsive: true,
    plugins: {
        legend: {
            position: 'top',
        },
        title: {
            display: true,
            text: '',
        },
    },
};

// DashboardCharts component
const DashboardCharts = ({ tickets, users }) => {
    const [selectedPeriod, setSelectedPeriod] = useState('jour');
    const [selectedPeriodUser, setSelectedPeriodUser] = useState('jour');

    const filteredTickets = filterByPeriod(tickets, selectedPeriod);
    const filteredUsers = filterByPeriod(users, selectedPeriodUser);

    const revenueDataTickets = generateChartData(filteredTickets, selectedPeriod, true);
    const revenueDataUsers = generateChartData(filteredUsers, selectedPeriodUser, false);

    const renderPeriodButtons = (period, setPeriod) => (
        <div className="flex flex-wrap  md:justify-between mb-4">
            {['jour', 'semaine', 'mois', '3months', '6months', 'année', 'all'].map(p => (
                <button
                    key={p}
                    onClick={() => setPeriod(p)}
                    className={`px-2 py-1 m-1 md:m-0 rounded ${period === p ? 'bg-blue-500' : 'bg-gray-700'} text-white`}
                    aria-pressed={period === p}
                >
                    {p === '3months' ? '3 Mois' : p === '6months' ? '6 Mois' : p.charAt(0).toUpperCase() + p.slice(1)}
                </button>
            ))}
        </div>
    );

    // Regroup tickets by company for Doughnut chart
    const regroupTickets = tickets.reduce((acc, curr) => {
        const compagnie = curr.compagnie.nom; // Group by 'nom' or use curr.compagnie_id to group by id

        if (!acc[compagnie]) {
            acc[compagnie] = { compagnie: curr.compagnie.nom, nbreticket: 0 };
        }

        acc[compagnie].nbreticket += 1; // Increment by 1 per ticket

        return acc;
    }, {});

    const result = Object.values(regroupTickets);

    // Doughnut data for companies
    const doughnutData = {
        labels: result.map(item => item.compagnie), // Company names as labels
        datasets: [
            {
                label: 'Nombre de Tickets',
                data: result.map(item => item.nbreticket), // Number of tickets sold per company
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'], // Assign colors to each company
                hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
            },
        ],
    };

    return (
        <div className="grid grid-cols-1 md:mb-12 mb-80 h-96 md:grid-cols-2 gap-4 mt-4">
            <div className="bg-dark-800 p-4 rounded-lg text-white shadow-md dark:bg-gray-800">
                <h2 className="text-lg font-bold mb-4">Tendance des ventes des Tickets</h2>
                {renderPeriodButtons(selectedPeriod, setSelectedPeriod)}
                <Line data={revenueDataTickets} options={{ ...revenueOptions, title: { text: 'Tendance des ventes des Tickets' } }} />
            </div>
            <div className="bg-dark-800 p-4 rounded-lg text-white shadow-md dark:bg-gray-800">
                <h2 className="text-lg font-bold mb-4">Tendance des inscriptions des utilisateurs</h2>
                {renderPeriodButtons(selectedPeriodUser, setSelectedPeriodUser)}
                <Line data={revenueDataUsers} options={{ ...revenueOptions, title: { text: 'Tendance des inscriptions des utilisateurs' } }} />
            </div>
        </div>
    );
};

export default DashboardCharts;
