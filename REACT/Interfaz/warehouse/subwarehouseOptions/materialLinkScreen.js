import React, { useState, useEffect } from "react";
import { View, Text, Button, Alert, StyleSheet } from "react-native";
import { Picker } from "@react-native-picker/picker"; // Importación actualizada
import { BASE_URL } from "../../config";

export default function MaterialLinkForm() {
  const [supplies, setSupplies] = useState([]);
  const [selectedSupply, setSelectedSupply] = useState(null);
  const [materialType, setMaterialType] = useState("");
  const [materials, setMaterials] = useState([]);
  const [selectedMaterial, setSelectedMaterial] = useState(null);

  useEffect(() => {
    // Fetch supplies from the API
    fetch(`${BASE_URL}/PROYECTO4B-1/phpfiles/react/supply_api.php`)
      .then((response) => response.json())
      .then((data) => setSupplies(data))
      .catch((error) =>
        console.error("Error al obtener los suministros:", error)
      );
  }, []);

  useEffect(() => {
    if (materialType) {
      // Fetch materials based on the selected type
      fetch(
        `${BASE_URL}/PROYECTO4B-1/phpfiles/react/material_link_api.php?material_type=${materialType}`
      )
        .then((response) => response.json())
        .then((data) => setMaterials(data))
        .catch((error) =>
          console.error("Error al obtener los materiales:", error)
        );
    }
  }, [materialType]);

  const handleSubmit = () => {
    if (!selectedSupply || !materialType || !selectedMaterial) {
      Alert.alert(
        "Error",
        "Por favor, completa todos los campos antes de enviar."
      );
      return;
    }

    const payload = {
      id_supply: selectedSupply,
      id_material_hardware:
        materialType === "hardware" ? selectedMaterial : null,
      id_material_component:
        materialType === "component" ? selectedMaterial : null,
      id_material_physical:
        materialType === "physical" ? selectedMaterial : null,
    };

    fetch(`${BASE_URL}/PROYECTO4B-1/phpfiles/react/material_link_api.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Error al crear la asociación");
        }
        return response.json();
      })
      .then(() => Alert.alert("Éxito", "Asociación creada con éxito"))
      .catch((error) => console.error("Error:", error));
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Asociar Material a Suministro</Text>

      <Text style={styles.label}>Suministro:</Text>
      <Picker
        selectedValue={selectedSupply}
        onValueChange={(itemValue) => setSelectedSupply(itemValue)}
        style={styles.picker}
      >
        <Picker.Item label="Seleccionar suministro" value={null} />
        {supplies.map((supply) => (
          <Picker.Item
            key={supply.id_supply}
            label={`ID: ${supply.id_supply}, Cantidad: ${supply.quantity}`}
            value={supply.id_supply}
          />
        ))}
      </Picker>

      <Text style={styles.label}>Tipo de Material:</Text>
      <Picker
        selectedValue={materialType}
        onValueChange={(itemValue) => setMaterialType(itemValue)}
        style={styles.picker}
      >
        <Picker.Item label="Seleccionar tipo" value="" />
        <Picker.Item label="Hardware" value="hardware" />
        <Picker.Item label="Componente" value="component" />
        <Picker.Item label="Físico" value="physical" />
      </Picker>

      {materialType && (
        <>
          <Text style={styles.label}>Material:</Text>
          <Picker
            selectedValue={selectedMaterial}
            onValueChange={(itemValue) => setSelectedMaterial(itemValue)}
            style={styles.picker}
          >
            <Picker.Item label="Seleccionar material" value={null} />
            {materials.map((material) => (
              <Picker.Item
                key={material.id_material}
                label={`Modelo: ${material.model}, Marca: ${material.brand}`}
                value={material.id_material}
              />
            ))}
          </Picker>
        </>
      )}

      <Button title="Asociar" onPress={handleSubmit} />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 20,
    backgroundColor: "#F5F5F5",
  },
  title: {
    fontSize: 24,
    fontWeight: "bold",
    marginBottom: 20,
    textAlign: "center",
  },
  label: {
    fontSize: 16,
    fontWeight: "bold",
    marginTop: 10,
  },
  picker: {
    height: 50,
    backgroundColor: "#FFF",
    marginVertical: 10,
    borderWidth: 1,
    borderColor: "#CCC",
    borderRadius: 5,
  },
});
