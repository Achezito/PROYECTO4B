import axios from "axios";
import { StatusBar } from "expo-status-bar";
import React, { useState } from "react";
import { Button, StyleSheet, TextInput, View } from "react-native";

const url = "http://localhost/PROYECTO4B-1/phpfiles/react/component_api.php"; // Ajusta la URL según tu entorno

export default function App() {
  const [components, setComponents] = useState([]);
  const [formData, setFormData] = useState({
    chipset: "",
    form_factor: "",
    socket_type: "",
    RAM_slots: "",
    max_RAM: "",
    expansion_slots: "",
    capacity: "",
    voltage: "",
    quantity: "",
    audio_channels: "",
    component_type: "",
    id_type: "",
  });

  const handleAddComponent = () => {
    for (let key in formData) {
      if (!formData[key]) {
        alert("Todos los campos son obligatorios");
        return;
      }
    }

    const newComponent = {
      ...formData,
      RAM_slots: parseInt(formData.RAM_slots),
      max_RAM: parseFloat(formData.max_RAM),
      expansion_slots: parseInt(formData.expansion_slots),
      capacity: parseFloat(formData.capacity),
      voltage: parseFloat(formData.voltage),
      quantity: parseInt(formData.quantity),
      audio_channels: parseInt(formData.audio_channels),
      id_type: parseInt(formData.id_type),
    };

    axios
      .post(url, newComponent)
      .then((response) => {
        alert(response.data.message);
        setFormData({
          chipset: "",
          form_factor: "",
          socket_type: "",
          RAM_slots: "",
          max_RAM: "",
          expansion_slots: "",
          capacity: "",
          voltage: "",
          quantity: "",
          audio_channels: "",
          component_type: "",
          id_type: "",
        });
        fetchComponents();
      })
      .catch((error) => {
        alert(
          error.response?.data?.error || "No se pudo agregar el componente",
        );
      });
  };

  return (
    <View style={styles.container}>
      {Object.keys(formData).map((key) => (
        <TextInput
          key={key}
          style={styles.input}
          placeholder={key.replace("_", " ")}
          value={formData[key]}
          onChangeText={(text) => setFormData({ ...formData, [key]: text })}
          placeholderTextColor="#aaa"
          keyboardType={
            [
              "RAM_slots",
              "max_RAM",
              "expansion_slots",
              "capacity",
              "voltage",
              "quantity",
              "audio_channels",
              "id_type",
            ].includes(key)
              ? "numeric"
              : "default"
          }
        />
      ))}

      <View style={styles.buttonContainer}>
        <Button
          title="Agregar Componente"
          onPress={handleAddComponent}
          color="#007bff"
        />
      </View>

      <StatusBar style="auto" />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#f0f4f8",
    padding: 20,
    justifyContent: "center",
  },
  title: {
    fontSize: 28,
    fontWeight: "bold",
    color: "#333",
    marginBottom: 20,
    textAlign: "center",
  },
  subtitle: {
    fontSize: 20,
    fontWeight: "bold",
    color: "#555",
    marginTop: 20,
    textAlign: "center",
  },
  input: {
    backgroundColor: "#fff",
    padding: 12,
    borderRadius: 10,
    marginBottom: 10,
    fontSize: 16,
    borderColor: "#ccc",
    borderWidth: 1,
  },
  buttonContainer: {
    marginTop: 10,
  },
  item: {
    backgroundColor: "#fff",
    padding: 10,
    marginVertical: 5,
    borderRadius: 8,
    textAlign: "center",
  },
});
