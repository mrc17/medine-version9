import "../../css/style.css"

export default function InputLabel({ value, className = '', children, ...props }) {
    return (
        <label {...props} className={`block text-color font-medium text-sm ` + className}>
            {value ? value : children}
        </label>
    );
}
