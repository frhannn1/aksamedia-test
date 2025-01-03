import React, { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import axios from "axios";

export default function EditData() {
    const { id } = useParams(); // Ambil id dari parameter URL
    const [employee, setEmployee] = useState(null); // State untuk data pegawai
    const [loading, setLoading] = useState(true); // State untuk loading
    const navigate = useNavigate();

    useEffect(() => {
        const token = localStorage.getItem("authToken");
        if (token) {
            axios
                .get(`http://127.0.0.1:8000/api/employees`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                })
                .then((response) => {
                    setEmployee(response.data.data.employees); // Simpan data pegawai
                    setLoading(false); // Nonaktifkan loading
                })
                .catch((error) => {
                    console.error("Error fetching employee data:", error);
                    setLoading(false);
                });
        }
    }, [id]);

    const EditDataPegawai = ({ employeeId, divisions, onUpdate }) => {
        const [employee, setEmployee] = useState({
            name: "",
            phone: "",
            position: "",
            division_id: "",
            image: null,
        });
    }

    const handleUpdate = () => {
        const token = localStorage.getItem("authToken");
        if (token) {
            axios
                .put(
                    `http://127.0.0.1:8000/api/employees/${id}`,
                    employee,
                    {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    }
                )
                .then(() => {
                    alert("Data berhasil diperbarui");
                    navigate("/dashboard"); // Kembali ke dashboard
                })
                .catch((error) => {
                    console.error("Error updating employee data:", error);
                });
        }
    };

    const handleImageChange = (e) => {
        setFormData({ ...formData, images: e.target.files[0] });
    };

    if (loading) return <p>Loading...</p>;

    return (
        <div className="p-4">
            <h1 className="text-2xl mb-4">Edit Data Pegawai</h1>
            <form
                onSubmit={(e) => {
                    e.preventDefault();
                    handleUpdate();
                }}
            >
                <div className="mb-4">
                    <label className="block text-gray-700">Name:</label>
                    <input
                        type="text"
                        value={employee.name || ""}
                        onChange={(e) =>
                            setEmployee({ ...employee, name: e.target.value })
                        }
                        className="w-full border border-gray-300 rounded px-3 py-2"
                    />
                </div>
                <div className="mb-4">
                    <label className="block text-gray-700">Phone:</label>
                    <input
                        type="text"
                        value={employee.phone || ""}
                        onChange={(e) =>
                            setEmployee({ ...employee, phone: e.target.value })
                        }
                        className="w-full border border-gray-300 rounded px-3 py-2"
                    />
                </div>
                <div className="mb-4">
                    <label className="block text-gray-700">Position:</label>
                    <input
                        type="text"
                        value={employee.position || ""}
                        onChange={(e) =>
                            setEmployee({
                                ...employee,
                                position: e.target.value,
                            })
                        }
                        className="w-full border border-gray-300 rounded px-3 py-2"
                    />
                </div>
                <div>
                    <label htmlFor="division_id" className="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Divisi
                    </label>
                    <select
                        id="division_id"
                        name="division_id"
                        value={employee.division_id}
                        onChange={(e) =>
                            setEmployee({
                                ...employee,
                                division_id: e.target.value,
                            })
                        }
                        required
                        className="w-full mt-1 p-2 border border-gray-200 rounded-lg"
                    >
                        <option value="">Pilih Divisi</option>
                        <option value="1">Mobile Apps</option>
                        <option value="2">QA</option>
                        <option value="3">Full Stack</option>
                        <option value="4">Backend</option>
                        <option value="5">Frontend</option>
                        <option value="6">UI/UX Designer</option>
                    </select>
                </div>
                <div>
                    <label
                        htmlFor="image"
                        className="block text-sm font-medium text-gray-700 dark:text-gray-200"
                    >
                        Gambar
                    </label>
                    <input
                        type="file"
                        id="image"
                        onChange={handleImageChange}
                        className="w-full mt-1 p-2 border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>
                <button
                    type="submit"
                    className="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600"
                >
                    Update
                </button>
            </form>
        </div>
    );
}
