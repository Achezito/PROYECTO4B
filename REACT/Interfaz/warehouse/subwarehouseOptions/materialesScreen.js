import React, { useEffect, useState } from 'react';
import { View, Text, StyleSheet, ScrollView, TextInput } from 'react-native';

export default function MaterialsScreen({ route }) {
  const { id } = route.params; // Recibe el ID del subalmacén
  const [materials, setMaterials] = useState([]);
  const [filteredMaterials, setFilteredMaterials] = useState([]); // Materiales filtrados
  const [searchText, setSearchText] = useState(''); // Texto de búsqueda
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchMaterials();
  }, []);

  useEffect(() => {
    // Filtra los materiales en función del texto de búsqueda
    const filtered = materials.filter((item) =>
      item.Material.toLowerCase().includes(searchText.toLowerCase()) ||
      item.Categoría.toLowerCase().includes(searchText.toLowerCase()) ||
      item.Descripción.toLowerCase().includes(searchText.toLowerCase())
    );
    setFilteredMaterials(filtered);
  }, [searchText, materials]);

  const fetchMaterials = async () => {
    try {
      const response = await fetch(`http://localhost/PROYECTO4B-1/phpfiles/react/sub_warehouse_api.php?id_sub_warehouse=${id}`);
      const data = await response.json();
      console.log('Datos recibidos:', data); // Verifica los datos en la consola
      setMaterials(data); // Actualiza el estado con los datos recibidos
      setFilteredMaterials(data); // Inicializa los materiales filtrados
    } catch (error) {
      console.error('Error obteniendo materiales:', error);
    } finally {
      setLoading(false); // Cambia el estado de carga
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
              <Text style={styles.cardTitle}>{item.Material}</Text>
              <View style={styles.cardContent}>
                <Text style={styles.cardText}>
                  <Text style={styles.label}>Categoría: </Text>
                  {item.Categoría}
                </Text>
                <Text style={styles.cardText}>
                  <Text style={styles.label}>Descripción: </Text>
                  {item.Descripción}
                </Text>
                <Text style={styles.cardText}>
                  <Text style={styles.label}>Cantidad: </Text>
                  {item['Cantidad Disponible']}
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
    backgroundColor: 'rgb(33, 37, 41)',
    padding: 16,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: 'white',
    marginBottom: 20,
    textAlign: 'center',
  },
  searchInput: {
    backgroundColor: 'white',
    borderRadius: 10,
    padding: 10,
    marginBottom: 20,
    fontSize: 16,
    color: 'black',
  },
  loadingText: {
    fontSize: 18,
    color: 'white',
    textAlign: 'center',
  },
  scrollContainer: {
    paddingBottom: 20,
  },
  card: {
    backgroundColor: 'white',
    borderRadius: 10,
    padding: 16,
    marginBottom: 16,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.2,
    shadowRadius: 4,
    elevation: 3,
  },
  cardTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: 'rgb(42, 126, 209)',
    marginBottom: 10,
  },
  cardContent: {
    marginLeft: 10,
  },
  cardText: {
    fontSize: 14,
    color: 'rgb(33, 37, 41)',
    marginBottom: 8,
  },
  label: {
    fontWeight: 'bold',
    color: 'rgb(42, 126, 209)',
  },
  emptyText: {
    fontSize: 18,
    color: 'white',
    textAlign: 'center',
    marginTop: 20,
  },
});