import React, { useState, useEffect } from "react";
import { View, Text, TextInput, Button, Alert, StyleSheet } from "react-native";
import { Picker } from "@react-native-picker/picker"; // Importar Picker
import { BASE_URL } from "C:/xampp/htdocs/PROYECTO4B-1/REACT/Interfaz/config";

export default function CreateOrderScreen({ navigation }) {
  const [supplyQuantity, setSupplyQuantity] = useState("");
  const [status, setStatus] = useState(""); // Estado seleccionado
  const [statuses, setStatuses] = useState([]); // Lista de estados

  // Cargar los estados desde la API
  useEffect(() => {
    const fetchStatuses = async () => {
      try {
        const response = await fetch(
          `${BASE_URL}/PROYECTO4B-1/phpfiles/react/estatusapi.php`
        );
        if (!response.ok) {
          throw new Error("Error al cargar los estados");
        }
        const data = await response.json();
        setStatuses(data); // Guardar los estados en el estado local
      } catch (error) {
        console.error("Error al cargar los estados:", error.message);
        Alert.alert("Error", "No se pudieron cargar los estados.");
      }
    };

    fetchStatuses();
  }, []);

  const handleSubmit = async () => {
    if (!supplyQuantity || !status) {
      Alert.alert("Error", "Por favor, completa todos los campos.");
      return;
    }

    const payload = {
      supply_quantity: parseInt(supplyQuantity),
      id_status: parseInt(status), // Usar el estado seleccionado
      confirmation: 3, // Valor predeterminado
    };

    try {
      const response = await fetch(
        `${BASE_URL}/PROYECTO4B-1/phpfiles/react/order_api.php`,
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload),
        }
      );

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.error || "Error al crear la orden");
      }

      const data = await response.json();
      Alert.alert("Éxito", "Orden creada con éxito");
      navigation.goBack(); // Regresa a la pantalla anterior
    } catch (error) {
      console.error("Error al crear la orden:", error.message);
      Alert.alert("Error", error.message);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Crear Nueva Orden</Text>

      <Text style={styles.label}>Cantidad de Suministro:</Text>
      <TextInput
        style={styles.input}
        keyboardType="numeric"
        value={supplyQuantity}
        onChangeText={setSupplyQuantity}
        placeholder="Ingresa la cantidad"
        placeholderTextColor="gray"
      />

      <Text style={styles.label}>Estado:</Text>
      <Picker
        selectedValue={status}
        onValueChange={(itemValue) => setStatus(itemValue)}
        style={styles.picker}
      >
        <Picker.Item label="Seleccionar estado" value="" />
        {statuses.map((status) => (
          <Picker.Item
            key={status.id_status}
            label={status.description}
            value={status.id_status}
          />
        ))}
      </Picker>

      <Button title="Crear Orden" onPress={handleSubmit} />
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
