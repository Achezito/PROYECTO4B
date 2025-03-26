import React, { useState, useEffect } from "react";

const API_URL = "http://localhost/PROYECTO4B-1/phpfiles/react";

const RecepcionScreen = () => {
    const [materials, setMaterials] = useState([]);
  const [subWarehouses, setSubWarehouses] = useState([]);
  const [assignedMaterials, setAssignedMaterials] = useState([]);
  const [notification, setNotification] = useState(null);

  // Obtener materiales desde la API
  const fetchMaterials = async () => {
    try {
      const response = await fetch(`${API_URL}/received_material_api.php`);
      const result = await response.json(); // Cambié 'data' a 'result' para mayor claridad
  
      console.log("API Response:", result); // Depuración: verifica la respuesta de la API
  
      if (response.ok && result.success) {
        setMaterials(result.data); // Asigna el array de materiales al estado
      } else {
        setNotification({ message: result.error || "Error al obtener materiales", type: "error" });
      }
    } catch (error) {
      console.error("Error al conectar con la API:", error);
      setNotification({ message: "Error al conectar con la API", type: "error" });
    }
  };

  // Obtener subalmacenes desde la API
  const fetchSubWarehouses = async () => {
    try {
      const response = await fetch(`${API_URL}/sub_warehouse_api.php`);
      const result = await response.json();
  
      console.log("SubWarehouses API Response:", result); // Depuración: verifica la respuesta de la API
  
      if (response.ok && result.success) {
        setSubWarehouses(result.data); // Asegúrate de que `result.data` sea un array
      } else {
        setNotification({ message: result.error || "Error al obtener subalmacenes", type: "error" });
      }
    } catch (error) {
      console.error("Error al conectar con la API:", error);
      setNotification({ message: "Error al conectar con la API", type: "error" });
    }
  };

  const handleAssign = async (material) => {
    console.log("Material ID:", material.id_material);
    console.log("Material Category (id_category):", material.id_category);
    console.log("SubWarehouses:", subWarehouses);
  
    const subWarehouse = subWarehouses.find((sw) => sw.id_category === material.id_category);
  
    if (!subWarehouse) {
      setNotification({ message: `No hay subalmacén para la categoría ${material.id_category}`, type: "error" });
      return;
    }
  
    console.log("SubWarehouse ID:", subWarehouse.id_sub_warehouse);
  
    try {
      const response = await fetch(`${API_URL}/received_material_api.php`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id_material: material.id_material,
          sub_almacen: subWarehouse.id_sub_warehouse,
        }),
      });
  
      const data = await response.json();
      console.log("API Response:", data);
  
      if (response.ok) {
        setAssignedMaterials((prev) => [
          ...prev,
          { ...material, subWarehouseName: subWarehouse.location },
        ]);
        setNotification({ message: data.message || "Material asignado con éxito", type: "success" });
      } else {
        setNotification({ message: data.error || "Error al asignar material", type: "error" });
      }
    } catch (error) {
      console.error("Error al conectar con la API:", error);
      setNotification({ message: "Error al conectar con la API", type: "error" });
    }
  };

  // Cargar datos al montar el componente
  useEffect(() => {
    fetchMaterials();
    fetchSubWarehouses();
  }, []);

  return (
    <div style={styles.container}>
      {notification && (
        <div
          style={{
            ...styles.notification,
            ...(notification.type === "error"
              ? styles.error
              : notification.type === "success"
              ? styles.success
              : styles.info),
          }}
        >
          {notification.message}
        </div>
      )}

      <div style={styles.card}>
        <h2 style={styles.title}>Materiales Pendientes</h2>
        <div style={styles.table}>
  {Array.isArray(materials) && materials.length > 0 ? (
    materials.map((material) => (
      <div key={material.id_material} style={styles.row}>
        <span style={styles.cell}>{material.description}</span>
        <span style={styles.cell}>{material.id_category}</span>
        <button
          style={styles.assignButton}
          onClick={() => handleAssign(material)}
        >
          Asignar
        </button>
      </div>
    ))
  ) : (
    <p>No hay materiales disponibles</p>
  )}
</div>
      </div>

      {assignedMaterials.length > 0 && (
        <div style={styles.card}>
          <h2 style={styles.title}>Materiales Asignados</h2>
          <div style={styles.table}>
            {assignedMaterials.map((material, index) => (
              <div key={index} style={styles.row}>
                <span style={styles.cell}>{material.model}</span>
                <span style={styles.cell}>{material.subWarehouseName}</span>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
};

const styles = {
  container: {
    padding: "20px",
    fontFamily: "Arial, sans-serif",
    backgroundColor: "#FFF8F0",
    minHeight: "100vh",
  },
  card: {
    backgroundColor: "white",
    borderRadius: "8px",
    padding: "16px",
    marginBottom: "16px",
    boxShadow: "0 4px 8px rgba(0, 0, 0, 0.1)",
  },
  title: {
    fontSize: "18px",
    fontWeight: "bold",
    marginBottom: "12px",
    color: "#F97316",
  },
  table: {
    display: "flex",
    flexDirection: "column",
    gap: "8px",
  },
  row: {
    display: "flex",
    justifyContent: "space-between",
    alignItems: "center",
    padding: "8px",
    borderBottom: "1px solid #E5E7EB",
  },
  cell: {
    flex: 1,
    textAlign: "left",
  },
  assignButton: {
    backgroundColor: "#F97316",
    color: "white",
    border: "none",
    padding: "8px 12px",
    borderRadius: "4px",
    cursor: "pointer",
  },
  notification: {
    padding: "12px",
    borderRadius: "4px",
    marginBottom: "16px",
    color: "white",
    textAlign: "center",
  },
  success: {
    backgroundColor: "#10B981",
  },
  error: {
    backgroundColor: "#EF4444",
  },
  info: {
    backgroundColor: "#3B82F6",
  },
};

export default RecepcionScreen;