import React, { useEffect, useState } from 'react';
import { Alert, FlatList, StyleSheet, Text, TextInput, TouchableOpacity, View } from 'react-native';

export default function UpdateWarehouseScreen({ navigation }) {
  const [warehouses, setWarehouses] = useState([]); // Lista de almacenes
  const [selectedWarehouse, setSelectedWarehouse] = useState(null); // Almacén seleccionado
  const [warehouseName, setWarehouseName] = useState('');
  const [location, setLocation] = useState('');
  const [capacity, setCapacity] = useState('');
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    fetchWarehouses();
  }, []);

  const fetchWarehouses = async () => {
    try {
      const response = await fetch('http://localhost/PROYECTO4B-1/phpfiles/react/warehouse_api.php', {
        method: 'GET',
      });
      const data = await response.json();
      setWarehouses(data);
    } catch (error) {
      console.error('Error fetching warehouses:', error);
      Alert.alert('Error', 'Failed to fetch warehouses.');
    }
  };

  const handleUpdateWarehouse = async () => {
    if (!selectedWarehouse) {
      Alert.alert('Error', 'Please select a warehouse to update.');
      return;
    }

    if (!warehouseName || !location || !capacity) {
      Alert.alert('Error', 'Please fill out all fields before submitting.');
      return;
    }

    if (isNaN(capacity) || parseInt(capacity, 10) <= 0) {
      Alert.alert('Error', 'Capacity must be a positive number.');
      return;
    }

    setLoading(true);

    try {
      const response = await fetch('http://localhost/PROYECTO4B-1/phpfiles/react/warehouse_api.php', {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          id: selectedWarehouse.id_warehouse,
          name: warehouseName,
          location: location,
          capacity: parseInt(capacity, 10),
        }),
      });

      const result = await response.json();

      if (response.ok) {
        Alert.alert('Success', `Warehouse "${warehouseName}" updated successfully!`);
        setWarehouseName('');
        setLocation('');
        setCapacity('');
        setSelectedWarehouse(null);
        fetchWarehouses(); // Refresca la lista de almacenes
      } else {
        Alert.alert('Error', result.message || 'Failed to update warehouse.');
      }
    } catch (error) {
      console.error('Error updating warehouse:', error);
      Alert.alert('Error', 'An error occurred while updating the warehouse.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Update Warehouse</Text>

      <FlatList
        data={warehouses}
        keyExtractor={(item) => item.id_warehouse.toString()}
        renderItem={({ item }) => (
          <TouchableOpacity
            style={[
              styles.warehouseItem,
              selectedWarehouse?.id_warehouse === item.id_warehouse && styles.selectedItem,
            ]}
            onPress={() => {
              setSelectedWarehouse(item);
              setWarehouseName(item.name);
              setLocation(item.location);
              setCapacity(item.capacity.toString());
            }}
          >
            <Text style={styles.warehouseText}>{item.name}</Text>
          </TouchableOpacity>
        )}
        ListEmptyComponent={<Text style={styles.emptyText}>No warehouses available</Text>}
      />

      {selectedWarehouse && (
        <>
          <Text style={styles.label}>Warehouse Name</Text>
          <TextInput
            style={styles.input}
            placeholder="Enter warehouse name"
            value={warehouseName}
            onChangeText={setWarehouseName}
          />

          <Text style={styles.label}>Location</Text>
          <TextInput
            style={styles.input}
            placeholder="Enter warehouse location"
            value={location}
            onChangeText={setLocation}
          />

          <Text style={styles.label}>Capacity (units)</Text>
          <TextInput
            style={styles.input}
            placeholder="Enter warehouse capacity"
            keyboardType="numeric"
            value={capacity}
            onChangeText={setCapacity}
          />

          <TouchableOpacity style={styles.button} onPress={handleUpdateWarehouse} disabled={loading}>
            <Text style={styles.buttonText}>{loading ? 'Updating...' : 'Update Warehouse'}</Text>
          </TouchableOpacity>
        </>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f7fa',
    padding: 20,
  },
  title: {
    fontSize: 26,
    fontWeight: 'bold',
    color: '#2c3e50',
    marginBottom: 20,
    textAlign: 'center',
  },
  warehouseItem: {
    padding: 15,
    backgroundColor: '#ecf0f1',
    borderRadius: 10,
    marginBottom: 10,
  },
  selectedItem: {
    backgroundColor: '#3498db',
  },
  warehouseText: {
    fontSize: 16,
    color: '#2c3e50',
  },
  emptyText: {
    fontSize: 16,
    color: '#7f8c8d',
    textAlign: 'center',
    marginTop: 20,
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