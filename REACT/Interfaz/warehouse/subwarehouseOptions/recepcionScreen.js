import React, { useState, useEffect } from "react";
import { BASE_URL } from "C:/xampp/htdocs/PROYECTO4B-1/REACT/Interfaz/config";
import {
  View,
  Text,
  FlatList,
  TouchableOpacity,
  StyleSheet,
  Alert,
} from "react-native";

export default function RecepcionForm({ navigation }) {
  const [supplies, setSupplies] = useState([]);
  const [categories, setCategories] = useState([]);
  const [selectedSupply, setSelectedSupply] = useState(null);
  const [selectedCategory, setSelectedCategory] = useState(null);
  const [materials, setMaterials] = useState([]); // Estado para los materiales del suministro seleccionado

  useEffect(() => {
    // Cargar suministros pendientes
    fetch(
      `${BASE_URL}/PROYECTO4B-1/phpfiles/react/supply_api.php?status=Pendiente`
    )
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Error al cargar suministros: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        if (!Array.isArray(data)) {
          throw new Error(
            "El formato de los datos de suministros no es válido."
          );
        }
        setSupplies(data);
      })
      .catch((error) => {
        console.error("Error al cargar suministros:", error);
        Alert.alert(
          "Error",
          "No se pudieron cargar los suministros pendientes."
        );
      });

    // Cargar categorías
    fetch(`${BASE_URL}/PROYECTO4B-1/phpfiles/react/category_api.php`)
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Error al cargar categorías: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        if (!Array.isArray(data)) {
          throw new Error(
            "El formato de los datos de categorías no es válido."
          );
        }
        setCategories(data);
      })
      .catch((error) => {
        console.error("Error al cargar categorías:", error);
        Alert.alert("Error", "No se pudieron cargar las categorías.");
      });
  }, []);

  const handleSupplySelect = (supply) => {
    setSelectedSupply(supply);

    // Cargar materiales del suministro seleccionado
    fetch(
      `${BASE_URL}/PROYECTO4B-1/phpfiles/react/materials_api.php?id_supply=${supply.id_supply}`
    )
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Error al cargar materiales: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        if (!Array.isArray(data)) {
          throw new Error(
            "El formato de los datos de materiales no es válido."
          );
        }
        setMaterials(data);
      })
      .catch((error) => {
        console.error("Error al cargar materiales:", error);
        Alert.alert(
          "Error",
          "No se pudieron cargar los materiales del suministro seleccionado."
        );
      });
  };

  const handleSubmit = () => {
    if (!selectedSupply) {
      Alert.alert("Error", "Por favor, seleccione un suministro.");
      return;
    }

    if (!selectedCategory) {
      Alert.alert("Error", "Por favor, seleccione una categoría.");
      return;
    }

    const payload = {
      id_supply: selectedSupply.id_supply,
      id_category: selectedCategory.id_category, // Asegúrate de que esta clave exista
    };
    console.log("Payload enviado:", payload);

    fetch(`${BASE_URL}/PROYECTO4B-1/phpfiles/react/received_material_api.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        console.log("Respuesta del servidor:", data); // Agrega este registro
        if (data.success) {
          Alert.alert("Éxito", "Material registrado con éxito");
          // Recargar los datos de los suministros
          fetch(
            `${BASE_URL}/PROYECTO4B-1/phpfiles/react/supply_api.php?status=Pendiente`
          )
            .then((response) => {
              if (!response.ok) {
                throw new Error(
                  `Error al recargar suministros: ${response.status}`
                );
              }
              return response.json();
            })
            .then((data) => setSupplies(data))
            .catch((error) =>
              console.error("Error al recargar suministros:", error)
            );

          navigation.goBack();
        } else {
          Alert.alert("Error", data.error || "Error al registrar el material");
        }
      })
      .catch((error) => {
        console.error("Error al registrar material:", error);
        Alert.alert("Error", "No se pudo registrar el material.");
      });
  };

  const renderSupplyItem = ({ item }) => (
    <TouchableOpacity
      style={[
        styles.card,
        selectedSupply?.id_supply === item.id_supply && styles.cardSelected,
      ]}
      onPress={() => handleSupplySelect(item)}
    >
      <Text style={styles.cardTitle}>{item.supplier_name}</Text>
      <Text style={styles.cardText}>Cantidad: {item.supply_quantity}</Text>
      <Text style={styles.cardText}>Dirección: {item.supplier_address}</Text>
      <Text style={styles.cardText}>Estatus: {item.supply_status}</Text>
    </TouchableOpacity>
  );

  const renderCategoryItem = ({ item }) => (
    <TouchableOpacity
      style={[
        styles.card,
        selectedCategory?.id_category === item.id_category &&
          styles.cardSelected,
      ]}
      onPress={() => setSelectedCategory(item)}
    >
      <Text style={styles.cardTitle}>{item.description}</Text>
    </TouchableOpacity>
  );

  const renderMaterialItem = ({ item }) => (
    <View style={styles.materialCard}>
      <Text style={styles.materialText}>Modelo: {item.material_model}</Text>
      <Text style={styles.materialText}>Marca: {item.material_brand}</Text>
      <Text style={styles.materialText}>Tipo: {item.material_type}</Text>
    </View>
  );

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Recepción de Materiales</Text>

      <Text style={styles.sectionTitle}>Suministros Pendientes</Text>
      <FlatList
        data={supplies}
        renderItem={renderSupplyItem}
        keyExtractor={(item) => item.id_supply.toString()}
        horizontal
        showsHorizontalScrollIndicator={false}
        style={styles.list}
      />

      <Text style={styles.sectionTitle}>Categorías</Text>
      <FlatList
        data={categories}
        renderItem={renderCategoryItem}
        keyExtractor={(item) => item.id_category.toString()}
        horizontal
        showsHorizontalScrollIndicator={false}
        style={styles.list}
      />

      {selectedSupply && (
        <>
          <Text style={styles.sectionTitle}>Materiales del Suministro</Text>
          <FlatList
            data={materials}
            renderItem={renderMaterialItem}
            keyExtractor={(item, index) => index.toString()}
            style={styles.list}
          />
        </>
      )}

      <TouchableOpacity
        style={[
          styles.button,
          !(selectedSupply && selectedCategory) && styles.buttonDisabled,
        ]}
        onPress={handleSubmit}
        disabled={!(selectedSupply && selectedCategory)}
      >
        <Text style={styles.buttonText}>Registrar Material</Text>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 20, backgroundColor: "#FFF3E0" },
  title: {
    fontSize: 24,
    fontWeight: "bold",
    marginBottom: 20,
    color: "#E65100",
    textAlign: "center",
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: "bold",
    marginBottom: 10,
    color: "#BF360C",
  },
  list: { marginBottom: 20 },
  card: {
    backgroundColor: "#FFF",
    borderRadius: 10,
    padding: 15,
    marginRight: 10,
    borderWidth: 1,
    borderColor: "#FF9800",
    width: 200,
  },
  cardSelected: {
    borderColor: "#E65100",
    borderWidth: 2,
  },
  cardTitle: { fontSize: 16, fontWeight: "bold", color: "#E65100" },
  cardText: { fontSize: 14, color: "#6D4C41" },
  materialCard: {
    backgroundColor: "#FFF",
    borderRadius: 10,
    padding: 10,
    marginBottom: 10,
    borderWidth: 1,
    borderColor: "#FF9800",
  },
  materialText: { fontSize: 14, color: "#6D4C41" },
  button: {
    backgroundColor: "#FF9800",
    padding: 15,
    borderRadius: 5,
    alignItems: "center",
  },
  buttonDisabled: {
    backgroundColor: "#FFCC80",
  },
  buttonText: { color: "white", fontSize: 16, fontWeight: "bold" },
});
