import React, { useState } from 'react';
import { Alert, StyleSheet, Text, TextInput, TouchableOpacity, View } from 'react-native';

export default function CreateWarehouseScreen() {
  const [warehouseName, setWarehouseName] = useState('');
  const [location, setLocation] = useState('');
  const [capacity, setCapacity] = useState('');

  const handleCreateWarehouse = () => {
    if (!warehouseName || !location || !capacity) {
      Alert.alert('Error', 'Please fill out all fields before submitting.');
      return;
    }
    Alert.alert('Success', `Warehouse "${warehouseName}" created successfully!`);
    setWarehouseName('');
    setLocation('');
    setCapacity('');
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Create New Warehouse</Text>
      
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

      <TouchableOpacity style={styles.button} onPress={handleCreateWarehouse}>
        <Text style={styles.buttonText}>Create Warehouse</Text>
      </TouchableOpacity>
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
