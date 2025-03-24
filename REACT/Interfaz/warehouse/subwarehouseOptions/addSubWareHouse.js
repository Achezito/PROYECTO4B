import React, { useState, useEffect } from 'react';
import { Alert, StyleSheet, Text, TextInput, TouchableOpacity, View, ActivityIndicator } from 'react-native';
import { Picker } from '@react-native-picker/picker'; // Asegúrate de instalar este paquete si no lo tienes

export default function AddSubWarehouseScreen({ route, navigation }) {
  const { warehouseId } = route.params; // Recibe el ID del almacén principal
  const [location, setLocation] = useState('');
  const [capacity, setCapacity] = useState('');
  const [categories, setCategories] = useState([]); // Lista de categorías
  const [selectedCategory, setSelectedCategory] = useState(''); // Valor inicial como cadena vacía
  const [loading, setLoading] = useState(false); // Estado para manejar el indicador de carga

  useEffect(() => {
    fetchCategories(); // Carga las categorías al montar el componente
  }, []);

  const fetchCategories = async () => {
    try {
      const response = await fetch('http://localhost/PROYECTO4B-1/phpfiles/react/category_api.php', {
        method: 'GET',
      });
      const data = await response.json();
      setCategories(data);
    } catch (error) {
      console.error('Error obteniendo categorías:', error);
      Alert.alert('Error', 'No se pudieron cargar las categorías.');
    }
  };

  const handleAddSubWarehouse = async () => {
    if (!location || !capacity || !selectedCategory) {
      Alert.alert('Error', 'Por favor, completa todos los campos antes de enviar.');
      return;
    }

    if (isNaN(capacity) || parseInt(capacity, 10) <= 0) {
      Alert.alert('Error', 'La capacidad debe ser un número positivo.');
      return;
    }

    setLoading(true); // Activa el indicador de carga

    try {
      const response = await fetch('http://localhost/PROYECTO4B-1/phpfiles/react/sub_warehouse_api.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            location: location,
            capacity: parseInt(capacity, 10),
            warehouse_id: warehouseId,
          id_category: selectedCategory, // Envía el ID de la categoría seleccionada
        }),
      });
      console.log({
          location: location,
          capacity: parseInt(capacity, 10),
          warehouse_id: warehouseId,
        id_category: selectedCategory,
      });
      const result = await response.json();

      if (response.ok) {
        Alert.alert('Éxito', `Subalmacén en "${location}" añadido correctamente.`);
        setLocation('');
        setCapacity('');
        setSelectedCategory(null);
        navigation.goBack(); // Regresa a la pantalla anterior
      } else {
        Alert.alert('Error', result.message || 'No se pudo añadir el subalmacén.');
      }
    } catch (error) {
      console.error('Error añadiendo subalmacén:', error);
      Alert.alert('Error', 'Ocurrió un error al añadir el subalmacén.');
    } finally {
      setLoading(false); // Desactiva el indicador de carga
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Añadir Subalmacén</Text>

      <Text style={styles.label}>Ubicación</Text>
      <TextInput
        style={styles.input}
        placeholder="Ingresa la ubicación del subalmacén"
        value={location}
        onChangeText={setLocation}
      />

      <Text style={styles.label}>Capacidad (unidades)</Text>
      <TextInput
        style={styles.input}
        placeholder="Ingresa la capacidad del subalmacén"
        keyboardType="numeric"
        value={capacity}
        onChangeText={setCapacity}
      />

      <Text style={styles.label}>Categoría</Text>
      <Picker
  selectedValue={selectedCategory}
  onValueChange={(itemValue) => setSelectedCategory(itemValue)}
  style={styles.picker}
>
  <Picker.Item label="Selecciona una categoría" value="" />
  {categories.map((category) => (
    <Picker.Item key={category.id_category} label={category.name} value={category.id_category} />
  ))}
</Picker>
      <TouchableOpacity
        style={styles.addCategoryButton}
        onPress={() => navigation.navigate('Category')} // Navega a la pantalla para añadir una categoría
      >
        <Text style={styles.addCategoryText}>Añadir nueva categoría</Text>
      </TouchableOpacity>

      <TouchableOpacity style={styles.button} onPress={handleAddSubWarehouse} disabled={loading}>
        <Text style={styles.buttonText}>{loading ? 'Añadiendo...' : 'Añadir Subalmacén'}</Text>
      </TouchableOpacity>

      {loading && <ActivityIndicator size="large" color="#3498db" />}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f7fa',
    padding: 20,
    justifyContent: 'center',
  },
  title: {
    fontSize: 26,
    fontWeight: 'bold',
    color: '#2c3e50',
    marginBottom: 20,
    textAlign: 'center',
  },
  label: {
    fontSize: 16,
    color: '#34495e',
    marginBottom: 8,
  },
  input: {
    height: 50,
    borderColor: '#bdc3c7',
    borderWidth: 1,
    borderRadius: 10,
    paddingHorizontal: 15,
    marginBottom: 15,
    backgroundColor: '#ecf0f1',
    fontSize: 16,
  },
  picker: {
    height: 50,
    borderColor: '#bdc3c7',
    borderWidth: 1,
    borderRadius: 10,
    marginBottom: 15,
    backgroundColor: '#ecf0f1',
  },
  addCategoryButton: {
    marginBottom: 15,
    alignItems: 'center',
  },
  addCategoryText: {
    color: '#3498db',
    fontSize: 16,
    fontWeight: 'bold',
  },
  button: {
    backgroundColor: '#3498db',
    paddingVertical: 15,
    borderRadius: 10,
    alignItems: 'center',
    marginTop: 10,
  },
  buttonText: {
    color: '#ffffff',
    fontSize: 16,
    fontWeight: 'bold',
  },
});