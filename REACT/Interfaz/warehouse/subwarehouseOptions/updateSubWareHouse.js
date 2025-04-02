import React, { useState, useEffect } from "react";
import {
  StyleSheet,
  View,
  Text,
  TextInput,
  TouchableOpacity,
  FlatList,
  Alert,
  ActivityIndicator,
} from "react-native";

export default function UpdateSubWarehouseScreen({ route, navigation }) {
  const { warehouseId } = route.params; // Recibe el ID del almacén desde la navegación

  const [subWarehouses, setSubWarehouses] = useState([]); // Lista de subalmacenes
  const [selectedSubWarehouse, setSelectedSubWarehouse] = useState(null); // Subalmacén seleccionado
  const [location, setLocation] = useState("");
  const [capacity, setCapacity] = useState("");
  const [selectedCategory, setSelectedCategory] = useState("");
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    fetchSubWarehouses();
    fetchCategories();
  }, []);

  const fetchSubWarehouses = async () => {
    try {
      const response = await fetch(
        `http://localhost/PROYECTO4B-1/phpfiles/react/sub_warehouse_api.php?id=${warehouseId}`,
      );
      const data = await response.json();
      setSubWarehouses(data);
    } catch (error) {
      console.error("Error obteniendo subalmacenes:", error);
      Alert.alert("Error", "No se pudieron cargar los subalmacenes.");
    }
  };

  const fetchCategories = async () => {
    try {
      const response = await fetch(
        "http://localhost/PROYECTO4B-1/phpfiles/react/category_api.php",
        {
          method: "GET",
        },
      );
      const data = await response.json();
      setCategories(data);
    } catch (error) {
      console.error("Error obteniendo categorías:", error);
      Alert.alert("Error", "No se pudieron cargar las categorías.");
    }
  };

  const handleUpdateSubWarehouse = async () => {
    if (!selectedSubWarehouse) {
      Alert.alert(
        "Error",
        "Por favor, selecciona un subalmacén para actualizar.",
      );
      return;
    }

    if (!location || !capacity || !selectedCategory) {
      Alert.alert(
        "Error",
        "Por favor, completa todos los campos antes de enviar.",
      );
      return;
    }

    if (isNaN(capacity) || parseInt(capacity, 10) <= 0) {
      Alert.alert("Error", "La capacidad debe ser un número positivo.");
      return;
    }

    setLoading(true);

    try {
      const response = await fetch(
        `http://localhost/PROYECTO4B-1/phpfiles/react/sub_warehouse_api.php`,
        {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            id_sub_warehouse: selectedSubWarehouse.ID,
            location: location,
            capacity: parseInt(capacity, 10),
            id_category: selectedCategory,
          }),
        },
      );

      const result = await response.json();

      if (response.ok) {
        Alert.alert(
          "Éxito",
          `Subalmacén "${location}" actualizado correctamente.`,
        );
        setLocation("");
        setCapacity("");
        setSelectedCategory("");
        setSelectedSubWarehouse(null);
        fetchSubWarehouses(); // Refresca la lista de subalmacenes
      } else {
        Alert.alert(
          "Error",
          result.message || "No se pudo actualizar el subalmacén.",
        );
      }
    } catch (error) {
      console.error("Error actualizando subalmacén:", error);
      Alert.alert("Error", "Ocurrió un error al actualizar el subalmacén.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Actualizar Subalmacén</Text>

      <FlatList
        data={subWarehouses}
        keyExtractor={(item) => item.ID.toString()}
        renderItem={({ item }) => (
          <TouchableOpacity
            style={[
              styles.subWarehouseItem,
              selectedSubWarehouse?.ID === item.ID && styles.selectedItem,
            ]}
            onPress={() => {
              setSelectedSubWarehouse(item);
              setLocation(item.Location || ""); // Valor predeterminado si Location es undefined
              setCapacity(item.Capacity ? item.Capacity.toString() : ""); // Maneja undefined o null
              setSelectedCategory(item.IdCategory || ""); // Valor predeterminado si IdCategory es undefined
            }}
          >
            <Text style={styles.subWarehouseText}>{item.Location}</Text>
          </TouchableOpacity>
        )}
        ListEmptyComponent={
          <Text style={styles.emptyText}>No hay subalmacenes disponibles</Text>
        }
      />

      {selectedSubWarehouse && (
        <>
          <Text style={styles.label}>Ubicación</Text>
          <TextInput
            style={styles.input}
            placeholder="Ingresa la nueva ubicación del subalmacén"
            value={location}
            onChangeText={setLocation}
          />

          <Text style={styles.label}>Capacidad (unidades)</Text>
          <TextInput
            style={styles.input}
            placeholder="Ingresa la nueva capacidad del subalmacén"
            keyboardType="numeric"
            value={capacity}
            onChangeText={setCapacity}
          />

          <Text style={styles.label}>Categoría</Text>
          <FlatList
            data={categories}
            keyExtractor={(item) => item.id_category.toString()}
            renderItem={({ item }) => (
              <TouchableOpacity
                style={[
                  styles.categoryItem,
                  selectedCategory === item.id_category && styles.selectedItem,
                ]}
                onPress={() => setSelectedCategory(item.id_category)}
              >
                <Text style={styles.categoryText}>{item.name}</Text>
              </TouchableOpacity>
            )}
            ListEmptyComponent={
              <Text style={styles.emptyText}>
                No hay categorías disponibles
              </Text>
            }
          />

          <TouchableOpacity
            style={styles.button}
            onPress={handleUpdateSubWarehouse}
            disabled={loading}
          >
            <Text style={styles.buttonText}>
              {loading ? "Actualizando..." : "Actualizar Subalmacén"}
            </Text>
          </TouchableOpacity>
        </>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#f5f7fa",
    padding: 20,
  },
  title: {
    fontSize: 26,
    fontWeight: "bold",
    color: "#2c3e50",
    marginBottom: 20,
    textAlign: "center",
  },
  subWarehouseItem: {
    padding: 15,
    backgroundColor: "#ecf0f1",
    borderRadius: 10,
    marginBottom: 10,
  },
  selectedItem: {
    backgroundColor: "#3498db",
  },
  subWarehouseText: {
    fontSize: 16,
    color: "#2c3e50",
  },
  emptyText: {
    fontSize: 16,
    color: "#7f8c8d",
    textAlign: "center",
    marginTop: 20,
  },
  label: {
    fontSize: 16,
    color: "#34495e",
    marginBottom: 8,
  },
  input: {
    height: 50,
    borderColor: "#bdc3c7",
    borderWidth: 1,
    borderRadius: 10,
    paddingHorizontal: 15,
    marginBottom: 15,
    backgroundColor: "#ecf0f1",
    fontSize: 16,
  },
  categoryItem: {
    padding: 15,
    backgroundColor: "#ecf0f1",
    borderRadius: 10,
    marginBottom: 10,
  },
  categoryText: {
    fontSize: 16,
    color: "#2c3e50",
  },
  button: {
    backgroundColor: "#3498db",
    paddingVertical: 15,
    borderRadius: 10,
    alignItems: "center",
    marginTop: 10,
  },
  buttonText: {
    color: "#ffffff",
    fontSize: 16,
    fontWeight: "bold",
  },
});
