import React, { useEffect, useState } from "react";
import { View, Text, StyleSheet, ScrollView, TextInput } from "react-native";
import { BASE_URL } from "C:/xampp/htdocs/PROYECTO4B-1/REACT/Interfaz/config";

export default function MaterialsScreen({ route }) {
  const { id } = route.params; // Recibe el ID del subalmacén
  const [materials, setMaterials] = useState([]);
  const [filteredMaterials, setFilteredMaterials] = useState([]); // Materiales filtrados
  const [searchText, setSearchText] = useState(""); // Texto de búsqueda
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchMaterials();
  }, []);

  useEffect(() => {
    // Filtra los materiales en función del texto de búsqueda
    const filtered = materials.filter((item) => {
      const material = item.Material?.toLowerCase() || ""; // Maneja undefined
      const categoria = item.Categoría?.toLowerCase() || ""; // Maneja undefined
      const descripcion = item.Descripción?.toLowerCase() || ""; // Maneja undefined

      return (
        material.includes(searchText.toLowerCase()) ||
        categoria.includes(searchText.toLowerCase()) ||
        descripcion.includes(searchText.toLowerCase())
      );
    });
    setFilteredMaterials(filtered);
  }, [searchText, materials]);

  const fetchMaterials = async () => {
    try {
      setLoading(true); // Activa el indicador de carga
      const response = await fetch(
        `${BASE_URL}/PROYECTO4B-1/phpfiles/react/sub_warehouse_material_api.php?id_sub_warehouse=${id}`
      );
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const data = await response.json();
      console.log("Datos recibidos:", data);

      // Verifica que la respuesta tenga éxito y que "data" sea un arreglo
      if (data.success && Array.isArray(data.data)) {
        setMaterials(data.data);
        setFilteredMaterials(data.data);
      } else {
        console.error("La respuesta no contiene un arreglo válido:", data);
        setMaterials([]);
        setFilteredMaterials([]);
      }
    } catch (error) {
      console.error("Error obteniendo materiales:", error.message);
    } finally {
      setLoading(false); // Desactiva el indicador de carga
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Materiales del Subalmacén</Text>
      <TextInput
        style={styles.searchInput}
        placeholder="Buscar materiales..."
        placeholderTextColor="gray"
        value={searchText}
        onChangeText={(text) => setSearchText(text)} // Actualiza el texto de búsqueda
      />
      {loading ? (
        <Text style={styles.loadingText}>Cargando...</Text>
      ) : filteredMaterials.length > 0 ? (
        <ScrollView contentContainerStyle={styles.scrollContainer}>
          {filteredMaterials.map((item, index) => (
            <View key={index} style={styles.card}>
              <Text style={styles.cardTitle}> {item.Descripción}</Text>

              <View style={styles.cardContent}>
                <Text style={styles.cardText}>
                  <Text style={styles.label}>Material: </Text>
                  {item.Material}
                </Text>
                <Text style={styles.cardText}>
                  <Text style={styles.label}>Categoría: </Text>
                  {item.Categoría}
                </Text>
                <Text style={styles.cardText}>
                  <Text style={styles.label}>Cantidad: </Text>
                  {item["Cantidad Disponible"]}
                </Text>
              </View>
            </View>
          ))}
        </ScrollView>
      ) : (
        <Text style={styles.emptyText}>No hay materiales disponibles</Text>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "rgb(33, 37, 41)",
    padding: 16,
  },
  title: {
    fontSize: 24,
    fontWeight: "bold",
    color: "white",
    marginBottom: 20,
    textAlign: "center",
  },
  searchInput: {
    backgroundColor: "white",
    borderRadius: 10,
    padding: 10,
    marginBottom: 20,
    fontSize: 16,
    color: "black",
  },
  loadingText: {
    fontSize: 18,
    color: "white",
    textAlign: "center",
  },
  scrollContainer: {
    paddingBottom: 20,
  },
  card: {
    backgroundColor: "white",
    borderRadius: 10,
    padding: 16,
    marginBottom: 16,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.2,
    shadowRadius: 4,
    elevation: 3,
  },
  cardTitle: {
    fontSize: 18,
    fontWeight: "bold",
    color: "rgb(42, 126, 209)",
    marginBottom: 10,
  },
  cardContent: {
    marginLeft: 10,
  },
  cardText: {
    fontSize: 14,
    color: "rgb(33, 37, 41)",
    marginBottom: 8,
  },
  label: {
    fontWeight: "bold",
    color: "rgb(42, 126, 209)",
  },
  emptyText: {
    fontSize: 18,
    color: "white",
    textAlign: "center",
    marginTop: 20,
  },
});
