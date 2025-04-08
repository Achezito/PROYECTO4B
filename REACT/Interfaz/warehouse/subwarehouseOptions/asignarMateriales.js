import React, { useState, useEffect } from "react";
import { View, Text, TextInput, Button, Alert, StyleSheet } from "react-native";
import { Picker } from "@react-native-picker/picker";
import { BASE_URL } from "../../config";

export default function AsignarMaterialesScreen({ navigation }) {
  const [materials, setMaterials] = useState([]); // Lista de materiales
  const [subWarehouses, setSubWarehouses] = useState([]); // Lista de subalmacenes
  const [selectedMaterial, setSelectedMaterial] = useState(""); // Material seleccionado
  const [selectedSubWarehouse, setSelectedSubWarehouse] = useState(""); // Subalmacén seleccionado
  const [assignQuantity, setAssignQuantity] = useState(""); // Cantidad a asignar

  // Cargar datos desde la API

  useEffect(() => {
    const fetchData = async () => {
      try {
        // Obtener materiales
        const materialsResponse = await fetch(
          `${BASE_URL}/PROYECTO4B-1/phpfiles/react/received_material_api.php`
        );
        const materialsData = await materialsResponse.json();
        setMaterials(materialsData);

        // Obtener subalmacenes
        const subWarehousesResponse = await fetch(
          `${BASE_URL}/PROYECTO4B-1/phpfiles/react/getSubwarehouses.php`
        );
        const subWarehousesData = await subWarehousesResponse.json();
        console.log("Subalmacenes cargados:", subWarehousesData); // Depuración
        setSubWarehouses(subWarehousesData);
      } catch (error) {
        console.error("Error al cargar los subalmacenes:", error.message);
        Alert.alert("Error", "No se pudieron cargar los subalmacenes.");
      }
    };

    fetchData();
  }, []);

  const handleAssignMaterial = async () => {
    // Validar los datos
    if (!selectedMaterial || !selectedSubWarehouse || !assignQuantity) {
      Alert.alert(
        "Error",
        "Por favor, selecciona un material, un subalmacén y especifica la cantidad a asignar."
      );
      return;
    }

    const payload = {
      action: "assign", // Indica que es una asignación de material
      id_material: parseInt(selectedMaterial),
      id_sub_warehouse: parseInt(selectedSubWarehouse),
      quantity: parseFloat(assignQuantity),
    };

    try {
      const response = await fetch(
        `${BASE_URL}/PROYECTO4B-1/phpfiles/react/sub_warehouse_material_api.php`, // Cambiado el endpoint
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload),
        }
      );

      if (!response.ok) {
        const errorText = await response.text();
        console.error("Respuesta del servidor:", errorText);
        throw new Error(
          `Error en el servidor: ${response.status} - ${errorText}`
        );
      }

      Alert.alert("Éxito", "Material asignado al subalmacén con éxito");
      setSelectedMaterial("");
      setSelectedSubWarehouse("");
      setAssignQuantity("");
    } catch (error) {
      console.error("Error al asignar el material:", error.message);
      Alert.alert("Error", `Error al asignar el material: ${error.message}`);
    }
  };
  return (
    <View style={styles.container}>
      <Text style={styles.title}>Asignar Material a Subalmacén</Text>

      {/* Selector de Material */}
      <Text style={styles.label}>Material:</Text>
      <Picker
        selectedValue={selectedMaterial}
        onValueChange={(itemValue) => setSelectedMaterial(itemValue)}
        style={styles.picker}
      >
        <Picker.Item label="Seleccionar material" value="" />
        {materials.map((material) => (
          <Picker.Item
            key={material.id_material}
            label={`${material.description} (Volumen: ${material.volume})`}
            value={material.id_material}
          />
        ))}
      </Picker>

      {/* Selector de Subalmacén */}
      <Text style={styles.label}>Subalmacén:</Text>
      <Picker
        selectedValue={selectedSubWarehouse}
        onValueChange={(itemValue) => setSelectedSubWarehouse(itemValue)}
        style={styles.picker}
      >
        <Picker.Item label="Seleccionar subalmacén" value="" />
        {Array.isArray(subWarehouses) &&
          subWarehouses.map((subWarehouse) => (
            <Picker.Item
              key={subWarehouse.id_sub_warehouse} // Clave única
              label={subWarehouse.location || "Sin ubicación"} // Etiqueta visible
              value={subWarehouse.id_sub_warehouse || ""} // Valor del subalmacén
            />
          ))}
      </Picker>

      {/* Cantidad a Asignar */}
      <Text style={styles.label}>Cantidad a Asignar:</Text>
      <TextInput
        style={styles.input}
        keyboardType="numeric"
        value={assignQuantity}
        onChangeText={setAssignQuantity}
        placeholder="Ingresa la cantidad"
        placeholderTextColor="gray"
      />

      <Button title="Asignar Material" onPress={handleAssignMaterial} />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 20,
    backgroundColor: "rgb(33, 37, 41)",
  },
  title: {
    fontSize: 24,
    fontWeight: "bold",
    color: "white",
    marginBottom: 16,
    textAlign: "center",
  },
  label: {
    fontSize: 16,
    fontWeight: "bold",
    color: "white",
    marginTop: 10,
  },
  input: {
    backgroundColor: "white",
    borderRadius: 10,
    padding: 10,
    marginBottom: 20,
    fontSize: 16,
    color: "black",
  },
  picker: {
    height: 50,
    backgroundColor: "white",
    marginVertical: 10,
    borderWidth: 1,
    borderColor: "#CCC",
    borderRadius: 5,
  },
});
