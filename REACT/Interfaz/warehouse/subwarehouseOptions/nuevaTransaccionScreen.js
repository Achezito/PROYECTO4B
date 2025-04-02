import React, { useEffect, useState } from "react";
import {
  Alert,
  Picker,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from "react-native";

export default function NuevaTransaccionScreen({ route, navigation }) {
  const { id } = route.params; // ID del subalmacén
  const [materials, setMaterials] = useState([]); // Lista de materiales
  const [selectedMaterial, setSelectedMaterial] = useState(""); // Material seleccionado
  const [quantity, setQuantity] = useState("");
  const [type, setType] = useState("inbound"); // Por defecto, tipo "entrada"
  const [availableQuantity, setAvailableQuantity] = useState(0); // Cantidad disponible del material seleccionado

  useEffect(() => {
    fetchMaterials();
  }, []);

  const fetchMaterials = async () => {
    try {
      const response = await fetch(
        `http://localhost/PROYECTO4B-1/phpfiles/react/sub_warehouse_material_api.php?id_sub_warehouse=${id}`,
      );
      const data = await response.json();
      if (response.ok) {
        setMaterials(data);
      } else {
        Alert.alert("Error", "No se pudieron cargar los materiales.");
      }
    } catch (error) {
      console.error("Error al cargar materiales:", error);
      Alert.alert("Error", "No se pudo conectar con el servidor.");
    }
  };

  const handleMaterialChange = (materialId) => {
    const material = materials.find(
      (m) => m.id_material === parseInt(materialId),
    );
    setSelectedMaterial(materialId);
    setAvailableQuantity(material ? material["Cantidad Disponible"] : 0);
  };

  const handleTransaction = async () => {
    if (!selectedMaterial || !quantity) {
      Alert.alert("Error", "Por favor, completa todos los campos.");
      return;
    }

    if (type === "outbound" && parseInt(quantity) > availableQuantity) {
      Alert.alert(
        "Error",
        "La cantidad no puede exceder la cantidad disponible.",
      );
      return;
    }

    if (parseInt(quantity) <= 0) {
      Alert.alert("Error", "La cantidad debe ser mayor a 0.");
      return;
    }

    try {
      const response = await fetch(
        "http://localhost/PROYECTO4B-1/phpfiles/react/transaction_api.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            id_material: parseInt(selectedMaterial),
            id_sub_warehouse: id,
            type,
            quantity: parseInt(quantity),
          }),
        },
      );

      const data = await response.json();
      if (response.ok) {
        Alert.alert("Éxito", "Transacción registrada exitosamente.");
        navigation.goBack(); // Regresa a la pantalla anterior
      } else {
        Alert.alert(
          "Error",
          data.error || "Hubo un problema al registrar la transacción.",
        );
      }
    } catch (error) {
      console.error("Error al registrar la transacción:", error);
      Alert.alert("Error", "No se pudo conectar con el servidor.");
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Nueva Transacción</Text>
      <Text style={styles.subtitle}>Subalmacén #{id}</Text>

      <View style={styles.pickerContainer}>
        <Text style={styles.pickerLabel}>Selecciona un Material:</Text>
        <Picker
          selectedValue={selectedMaterial}
          style={styles.picker}
          onValueChange={handleMaterialChange}
        >
          <Picker.Item label="Selecciona un material" value={null} />
          {materials.map((material) => (
            <Picker.Item
              key={material.id_material}
              label={`${material.Material} ${
                type === "outbound"
                  ? `(Disponible: ${material["Cantidad Disponible"]})`
                  : ""
              }`}
              value={material.id_material}
            />
          ))}
        </Picker>
      </View>

      <TextInput
        style={styles.input}
        placeholder="Cantidad"
        placeholderTextColor="gray"
        keyboardType="numeric"
        value={quantity}
        onChangeText={setQuantity}
      />

      <View style={styles.pickerContainer}>
        <Text style={styles.pickerLabel}>Tipo de Transacción:</Text>
        <Picker
          selectedValue={type}
          style={styles.picker}
          onValueChange={(itemValue) => {
            setType(itemValue);
            setQuantity(""); // Reinicia la cantidad al cambiar el tipo
          }}
        >
          <Picker.Item label="Entrada" value="inbound" />
          <Picker.Item label="Salida" value="outbound" />
        </Picker>
      </View>

      <TouchableOpacity style={styles.button} onPress={handleTransaction}>
        <Text style={styles.buttonText}>Registrar Transacción</Text>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "rgb(33, 37, 41)",
    padding: 20,
    justifyContent: "center",
  },
  title: {
    fontSize: 24,
    fontWeight: "bold",
    color: "white",
    textAlign: "center",
    marginBottom: 10,
  },
  subtitle: {
    fontSize: 18,
    color: "white",
    textAlign: "center",
    marginBottom: 20,
  },
  pickerContainer: {
    marginBottom: 20,
  },
  pickerLabel: {
    fontSize: 16,
    color: "white",
    marginBottom: 5,
  },
  picker: {
    backgroundColor: "white",
    borderRadius: 10,
    color: "black",
  },
  input: {
    backgroundColor: "white",
    borderRadius: 10,
    padding: 10,
    marginBottom: 20,
    fontSize: 16,
    color: "black",
  },
  button: {
    backgroundColor: "rgb(42, 126, 209)",
    padding: 15,
    borderRadius: 10,
    alignItems: "center",
  },
  buttonText: {
    fontSize: 18,
    color: "white",
    fontWeight: "bold",
  },
});
