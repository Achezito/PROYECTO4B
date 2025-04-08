import React, { useState, useEffect } from "react";
import { View, Text, TextInput, Button, Alert, StyleSheet } from "react-native";
import { Picker } from "@react-native-picker/picker";
import { BASE_URL } from "../../config";

export default function RecepcionScreen({ navigation }) {
  const [supplies, setSupplies] = useState([]); // Lista de suministros
  const [categories, setCategories] = useState([]); // Lista de categorías
  const [selectedSupply, setSelectedSupply] = useState(""); // Suministro seleccionado
  const [description, setDescription] = useState(""); // Descripción del material
  const [serialNumber, setSerialNumber] = useState(""); // Número de serie
  const [selectedCategory, setSelectedCategory] = useState(""); // Categoría seleccionada
  const [volume, setVolume] = useState(""); // Volumen

  // Cargar datos desde la API
  useEffect(() => {
    const fetchData = async () => {
      try {
        const suppliesResponse = await fetch(
          `${BASE_URL}/PROYECTO4B-1/phpfiles/react/supply_api.php`
        );
        const suppliesData = await suppliesResponse.json();
        setSupplies(suppliesData);

        const categoriesResponse = await fetch(
          `${BASE_URL}/PROYECTO4B-1/phpfiles/react/category_api.php`
        );
        const categoriesData = await categoriesResponse.json();
        setCategories(categoriesData);
      } catch (error) {
        console.error("Error al cargar los datos:", error.message);
        Alert.alert("Error", "No se pudieron cargar los datos.");
      }
    };

    fetchData();
  }, []);

  const handleRegisterMaterial = async () => {
    // Validar los datos
    if (
      !selectedSupply ||
      !description ||
      !serialNumber ||
      !selectedCategory ||
      !volume
    ) {
      Alert.alert(
        "Error",
        "Por favor, completa todos los campos obligatorios."
      );
      return;
    }

    const payload = {
      action: "register", // Indica que es un registro de material recibido
      id_supply: parseInt(selectedSupply),
      description,
      serial_number: serialNumber,
      id_category: parseInt(selectedCategory),
      volume: parseFloat(volume),
    };

    try {
      const response = await fetch(
        `${BASE_URL}/PROYECTO4B-1/phpfiles/react/received_material_api.php`,
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

      Alert.alert("Éxito", "Material recibido registrado con éxito");
      setSelectedSupply("");
      setDescription("");
      setSerialNumber("");
      setSelectedCategory("");
      setVolume("");
    } catch (error) {
      console.error("Error al registrar el material:", error.message);
      Alert.alert("Error", `Error al registrar el material: ${error.message}`);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Registrar Material Recibido</Text>

      <Text style={styles.label}>Suministro:</Text>
      <Picker
        selectedValue={selectedSupply}
        onValueChange={(itemValue) => setSelectedSupply(itemValue)}
        style={styles.picker}
      >
        <Picker.Item label="Seleccionar suministro" value="" />
        {supplies.map((supply) => (
          <Picker.Item
            key={supply.id_supply}
            label={`Suministro #${supply.id_supply}`}
            value={supply.id_supply}
          />
        ))}
      </Picker>

      <Text style={styles.label}>Descripción:</Text>
      <TextInput
        style={styles.input}
        value={description}
        onChangeText={setDescription}
        placeholder="Ingresa la descripción del material"
        placeholderTextColor="gray"
      />

      <Text style={styles.label}>Número de Serie:</Text>
      <TextInput
        style={styles.input}
        value={serialNumber}
        onChangeText={setSerialNumber}
        placeholder="Ingresa el número de serie"
        placeholderTextColor="gray"
      />

      <Text style={styles.label}>Categoría:</Text>
      <Picker
        selectedValue={selectedCategory}
        onValueChange={(itemValue) => setSelectedCategory(itemValue)}
        style={styles.picker}
      >
        <Picker.Item label="Seleccionar categoría" value="" />
        {categories.map((category) => (
          <Picker.Item
            key={category.id_category}
            label={category.name}
            value={category.id_category}
          />
        ))}
      </Picker>

      <Text style={styles.label}>Volumen:</Text>
      <TextInput
        style={styles.input}
        keyboardType="numeric"
        value={volume}
        onChangeText={setVolume}
        placeholder="Ingresa el volumen"
        placeholderTextColor="gray"
      />

      <Button title="Registrar Material" onPress={handleRegisterMaterial} />
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
